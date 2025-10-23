<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'afluencia_turistica',
        'num_reservas',
        'porcentaje_ocupacion',
        'clima',
        'dia_festivo',
        'model_version'
    ];

    protected $casts = [
        'date' => 'date',
        'dia_festivo' => 'boolean',
        'porcentaje_ocupacion' => 'float',
    ];
}
