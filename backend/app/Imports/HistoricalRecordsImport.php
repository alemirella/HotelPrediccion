<?php

namespace App\Imports;

use App\Models\HistoricalRecord;
use PhpOffice\PhpSpreadsheet\IOFactory;

class HistoricalRecordsImport
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function import($filePath)
    {
        // Cargar el archivo Excel
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Iterar sobre las filas (empezamos desde la fila 2 para saltar cabecera)
        foreach ($sheet->getRowIterator(2) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            // Guardar en la base de datos
            HistoricalRecord::create([
                'user_id' => $this->userId,
                'date' => $rowData[0],          // Columna A → Fecha
                'occupancy_rate' => $rowData[1], // Columna B → Ocupación
                'rooms_sold' => $rowData[2],     // Columna C → Habitaciones vendidas
            ]);
        }
    }
}
