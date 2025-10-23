<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoricalRecord;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class HistoricalRecordController extends Controller
{
    /**
     * Mostrar formulario de creación manual.
     */
    public function create()
    {
        return view('historical_records.create');
    }

    /**
     * Guardar un registro histórico ingresado manualmente.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'clima' => 'required|integer|in:1,2,3,4',
            'afluencia_turistica' => 'required|integer|min:0',
            'num_reservas' => 'required|integer|min:0',
            'porcentaje_ocupacion' => 'required|numeric|min:0|max:100',
            'dia_festivo' => 'required|boolean',
            'meta' => 'nullable|string',
        ]);

        $metaInput = $request->input('meta');
        $meta = $metaInput
            ? (json_decode($metaInput, true) ?? ['notes' => $metaInput])
            : null;

        HistoricalRecord::create([
            'user_id'              => Auth::id(),
            'date'                 => $request->date,
            'clima'                => (int)$request->clima,
            'afluencia_turistica'  => (int)$request->afluencia_turistica,
            'num_reservas'         => (int)$request->num_reservas,
            'porcentaje_ocupacion' => (float)$request->porcentaje_ocupacion,
            'dia_festivo'          => (bool)$request->dia_festivo,
            'meta'                 => $meta,
        ]);

        return redirect()->route('dashboard')->with('success', '✅ Registro guardado correctamente.');
    }

    /**
     * Importar registros históricos desde un archivo Excel o CSV.
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
            if ($index === 1) continue; // Saltar encabezado

            $rawDate   = $row['B'] ?? null; // Fecha
            $clima     = $row['C'] ?? null; // Clima
            $afluencia = $row['D'] ?? null; // Afluencia turística
            $reservas  = $row['E'] ?? null; // Reservas
            $ocupacion = $row['F'] ?? null; // % Ocupación
            $festivo   = $row['G'] ?? 0;    // Día festivo

            // --- Conversión de fecha ---
            $date = null;
            if ($rawDate) {
                $rawDate = trim($rawDate);

                // Si Excel la guarda como número
                if (is_numeric($rawDate)) {
                    try {
                        $date = ExcelDate::excelToDateTimeObject($rawDate)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $date = null;
                    }
                } else {
                    // Si está en formato texto (01/01/2025 o 01-01-2025)
                    $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d'];
                    foreach ($formats as $format) {
                        $dateObj = \DateTime::createFromFormat($format, $rawDate);
                        if ($dateObj) {
                            $date = $dateObj->format('Y-m-d');
                            break;
                        }
                    }
                }
            }

            // Guardar solo si tiene fecha y datos válidos
            if ($date && $clima !== null && $afluencia !== null && $reservas !== null) {
                HistoricalRecord::create([
                    'user_id'              => Auth::id(),
                    'date'                 => $date,
                    'clima'                => (int)$clima,
                    'afluencia_turistica'  => (int)$afluencia,
                    'num_reservas'         => (int)$reservas,
                    'porcentaje_ocupacion' => (float)$ocupacion,
                    'dia_festivo'          => (bool)$festivo,
                ]);
                $imported++;
            }
        }

        return redirect()->route('dashboard')
            ->with('success', "✅ Se importaron {$imported} registros correctamente.");
    }
}
