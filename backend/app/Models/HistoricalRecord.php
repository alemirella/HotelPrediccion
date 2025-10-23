<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'clima',
        'afluencia_turistica',
        'num_reservas',
        'porcentaje_ocupacion',
        'dia_festivo',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'date' => 'date',
        'dia_festivo' => 'boolean',
        'porcentaje_ocupacion' => 'float',
    ];

    /**
     * Relación con el usuario (hotel).
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
