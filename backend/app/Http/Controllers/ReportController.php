<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prediction;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PredictionsExport;

class ReportController extends Controller
{
    // Exportar a PDF
    public function exportPDF()
    {
        $predictions = Prediction::all();
        $pdf = Pdf::loadView('predictions.pdf', compact('predictions'));
        return $pdf->download('predicciones.pdf');
    }

    // Exportar a Excel
    public function exportExcel()
    {
        return Excel::download(new PredictionsExport, 'predicciones.xlsx');
    }
}
