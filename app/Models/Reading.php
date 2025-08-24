<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reading extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'temperature',
        'conveyor_speed',
        'recorded_at'
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'temperature' => 'float',
        'conveyor_speed' => 'float',
    ];

    /**
     * Relasi balik: setiap reading milik satu machine
     */
    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }
}
