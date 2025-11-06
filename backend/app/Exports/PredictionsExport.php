<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ExportController extends Controller
{
    public function exportUsuarios()
    {
        $filePath = storage_path('app/usuarios.xlsx');

        SimpleExcelWriter::create($filePath)
            ->addRows(User::select('id', 'name', 'email', 'created_at')->get()->toArray());

        return response()->download($filePath);
    }
}
