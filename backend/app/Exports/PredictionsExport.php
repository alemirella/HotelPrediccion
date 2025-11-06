<?php

namespace App\Exports;

use App\Models\Prediction;
use Maatwebsite\Excel\Concerns\FromCollection;

class PredictionsExport implements FromCollection
{
    public function collection()
    {
        return Prediction::all();
    }
}
