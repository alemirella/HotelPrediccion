<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HistoricalRecord extends Model
{
    protected $table = 'historical_records';

    protected $fillable = [
        'user_id',
        'date',
        'clima',
        'afluencia_turistica',
        'num_reservas',
        'porcentaje_ocupacion',
        'dia_festivo',
        'meta'
    ];

    protected $casts = [
        'date' => 'date',
        'meta' => 'array',
        'dia_festivo' => 'boolean',
    ];

    public static function insertUsingSP($data)
    {
        return DB::statement(
            "CALL sp_insert_historical_record(?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['user_id'],
                $data['date'],
                $data['clima'],
                $data['afluencia_turistica'],
                $data['num_reservas'],
                $data['porcentaje_ocupacion'],
                $data['dia_festivo'],
                isset($data['meta']) ? json_encode($data['meta']) : null
            ]
        );
    }
}
