<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoricalRecord;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class HistoricalRecordController extends Controller
{
    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('historical_records.create');
    }

    /**
     * Guardar registro histórico manual.
     */
    public function store(Request $request)
    {
        // Validaciones
        $request->validate([
            'date' => 'required|date',
            'demand_count' => 'required|integer|min:0',
            'meta' => 'nullable|string',
        ]);

        // Procesar meta (texto libre o JSON válido)
        $metaInput = $request->input('meta');
        if ($metaInput) {
            $decoded = json_decode($metaInput, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $meta = $decoded;
            } else {
                $meta = ['notes' => $metaInput];
            }
        } else {
            $meta = null;
        }

        // Crear registro
        HistoricalRecord::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'demand_count' => $request->demand_count,
            'meta' => $meta,
        ]);

        return redirect()->route('dashboard')->with('success', 'Registro guardado correctamente ✅');
    }

    /**
     * Importar registros históricos desde un archivo Excel.
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        $imported = 0;

        foreach ($rows as $index => $row) {
            // Saltar cabecera (asumimos que está en la primera fila)
            if ($index === 1) {
                continue;
            }

            $date = $row['A'] ?? null;
            $demand = $row['B'] ?? null;
            $meta = $row['C'] ?? null;

            if ($date && $demand !== null) {
                HistoricalRecord::create([
                    'user_id' => Auth::id(),
                    'date' => $date,
                    'demand_count' => (int) $demand,
                    'meta' => $meta ? ['notes' => $meta] : null,
                ]);

                $imported++;
            }
        }

        return redirect()->route('dashboard')->with('success', "✅ Se importaron {$imported} registros desde Excel");
    }
}
