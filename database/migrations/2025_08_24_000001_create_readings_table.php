<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained('machines')->cascadeOnDelete();
            $table->float('temperature');
            $table->float('conveyor_speed');
            $table->timestamp('recorded_at');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('readings');
    }
};
