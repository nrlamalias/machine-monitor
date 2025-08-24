<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use App\Models\Machine;
use App\Models\Reading;
use Carbon\Carbon;

class MachineMonitorCommand extends Command
{
    protected $signature = 'machine:monitor
                            {--setup}
                            {--add-reading=}
                            {--simulate=}
                            {--status}';

    protected $description = 'Memantau mesin chamber: setup, tambah reading, simulasi, status';

    public function handle(): int
    {
        if ($this->option('help') || count(array_filter([
            $this->option('setup'),
            $this->option('add-reading'),
            $this->option('simulate'),
            $this->option('status')
        ])) !== 1) {
            $this->showUsage();
            return self::SUCCESS;
        }

        if ($this->option('setup')) return $this->runSetup();
        if ($this->option('add-reading')) return $this->runAddReading((int)$this->option('add-reading'));
        if ($this->option('simulate')) return $this->runSimulate((int)$this->option('simulate'));
        if ($this->option('status')) return $this->runStatus();

        return self::SUCCESS;
    }

    private function showUsage(): void
    {
        $this->line('Usage:');
        $this->line('  php artisan machine:monitor --setup');
        $this->line('  php artisan machine:monitor --add-reading 1');
        $this->line('  php artisan machine:monitor --simulate 5');
        $this->line('  php artisan machine:monitor --status');
    }

    private function runSetup(): int
    {
        if (config('database.default') === 'sqlite') {
            $path = config('database.connections.sqlite.database');
            if ($path && $path !== ':memory:' && !File::exists($path)) {
                File::ensureDirectoryExists(dirname($path));
                File::put($path, '');
                $this->info("Membuat SQLite DB: {$path}");
            }
        }

        Artisan::call('migrate', ['--force' => true]);
        $this->line(Artisan::output());

        if (Machine::count() === 0) {
            Machine::create(['name'=>'Mesin A','location'=>'Line 1']);
            Machine::create(['name'=>'Mesin B','location'=>'Line 2']);
            Machine::create(['name'=>'Mesin C','location'=>'Line 3']);
            $this->info('3 mesin contoh ditambahkan.');
        }
        return self::SUCCESS;
    }

    private function runAddReading(int $machineId): int
    {
        $m = Machine::find($machineId);
        if (!$m) {
            $this->error("Mesin id {$machineId} tidak ditemukan");
            return self::FAILURE;
        }
        $temp = (float)$this->ask('Masukkan suhu (20–100 °C)');
        $speed = (float)$this->ask('Masukkan kecepatan conveyor (0.5–5.0)');
        Reading::create([
            'machine_id'=>$m->id,
            'temperature'=>$temp,
            'conveyor_speed'=>$speed,
            'recorded_at'=>Carbon::now(),
        ]);
        $this->info('Reading tersimpan');
        return self::SUCCESS;
    }

    private function runSimulate(int $count): int
    {
        $machines = Machine::all();
        if ($machines->isEmpty()) {
            $this->error('Belum ada mesin');
            return self::FAILURE;
        }
        for ($i=0; $i<$count; $i++) {
            $m = $machines->random();
            Reading::create([
                'machine_id'=>$m->id,
                'temperature'=>rand(20,100),
                'conveyor_speed'=>rand(1,5),
                'recorded_at'=>Carbon::now(),
            ]);
        }
        $this->info("{$count} reading acak ditambahkan");
        return self::SUCCESS;
    }

    private function runStatus(): int
    {
        $machines = Machine::with('latestReading')->get();
        $rows = [];
        foreach ($machines as $m) {
            $r = $m->latestReading;
            $rows[] = [
                $m->id,
                $m->name,
                $m->location,
                $r?->temperature ?? '-',
                $r?->conveyor_speed ?? '-',
                $r?->recorded_at ?? '-',
            ];
        }
        $this->table(['ID','Name','Location','Last Temp','Last Speed','Recorded At'],$rows);
        return self::SUCCESS;
    }
}
