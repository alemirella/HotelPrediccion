<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'predicted_count',
        'model_version',
        'input_features'
    ];
    protected $casts = [
        'input_features' => 'array',
        'date' => 'date',
    ];
}
