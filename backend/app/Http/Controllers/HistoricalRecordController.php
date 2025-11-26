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
     * AHORA usando Stored Procedure desde el Modelo (MVC correcto)
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

        // Procesar campo meta
        $metaInput = $request->input('meta');
        $meta = $metaInput
            ? (json_decode($metaInput, true) ?? ['notes' => $metaInput])
            : null;

        // LLAMADA AL STORED PROCEDURE DESDE EL MODELO
        HistoricalRecord::insertUsingSP([
            'user_id'              => Auth::id(),
            'date'                 => $request->date,
            'clima'                => (int)$request->clima,
            'afluencia_turistica'  => (int)$request->afluencia_turistica,
            'num_reservas'         => (int)$request->num_reservas,
            'porcentaje_ocupacion' => (float)$request->porcentaje_ocupacion,
            'dia_festivo'          => (bool)$request->dia_festivo,
            'meta'                 => $meta,
        ]);

        return redirect()->route('dashboard')
            ->with('success', '✅ Registro guardado correctamente mediante Stored Procedure.');
    }

    /**
     * Importar registros históricos desde un archivo Excel o CSV.
     * CADA registro se insertará usando el Stored Procedure.
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

            // Conversión de fecha
            $date = null;
            if ($rawDate) {
                $rawDate = trim($rawDate);

                if (is_numeric($rawDate)) {
                    try {
                        $date = ExcelDate::excelToDateTimeObject($rawDate)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $date = null;
                    }
                } else {
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

            // Validar datos
            if ($date && $clima !== null && $afluencia !== null && $reservas !== null) {

                // INSERTAR usando SP
                HistoricalRecord::insertUsingSP([
                    'user_id'              => Auth::id(),
                    'date'                 => $date,
                    'clima'                => (int)$clima,
                    'afluencia_turistica'  => (int)$afluencia,
                    'num_reservas'         => (int)$reservas,
                    'porcentaje_ocupacion' => (float)$ocupacion,
                    'dia_festivo'          => (bool)$festivo,
                    'meta'                 => null,
                ]);

                $imported++;
            }
        }

        return redirect()->route('dashboard')
            ->with('success', "✅ Se importaron {$imported} registros mediante Stored Procedure.");
    }
}