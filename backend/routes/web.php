<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HistoricalRecordController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

/*
|--------------------------------------------------------------------------
| Configuración para que Laravel encuentre las vistas en Frontend
|--------------------------------------------------------------------------
*/
View::addLocation(base_path('../Frontend/resources/views'));

/*
|--------------------------------------------------------------------------
| Rutas de acceso público (invitados)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Registro de usuarios
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    // Inicio de sesión
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Rutas protegidas (solo usuarios autenticados)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // ✅ Dashboard con datos de predicciones
    Route::get('/dashboard', function () {
        $predictions = \App\Http\Controllers\PredictionController::getDashboardData();
        return view('dashboard', compact('predictions'));
    })->name('dashboard');

    // Cerrar sesión
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Registro de datos históricos (HU-03)
    Route::resource('historical_records', HistoricalRecordController::class);

    // ✅ Carga masiva con Excel (importación de registros históricos)
    Route::post('/historical_records/import', [HistoricalRecordController::class, 'importExcel'])
        ->name('historical_records.import');

    // Predicciones y tablas (HU-04 y HU-05)
    Route::get('/predictions/create', [PredictionController::class, 'create'])->name('predictions.create');
    Route::post('/predictions', [PredictionController::class, 'store'])->name('predictions.store');
    Route::get('/predictions', [PredictionController::class, 'index'])->name('predictions.index');

    // Exportar registros históricos a CSV
    Route::get('/predictions/export', [PredictionController::class, 'export'])
        ->name('predictions.export');
});

Route::get('/predictions/export/pdf', [App\Http\Controllers\PredictionController::class, 'exportPDF'])->name('predictions.exportPDF');
Route::get('/predictions/export/excel', [App\Http\Controllers\PredictionController::class, 'exportExcel'])->name('predictions.exportExcel');

/*
|--------------------------------------------------------------------------
| Página principal
|--------------------------------------------------------------------------
|
| Si el usuario entra a la raíz ("/"), lo mandamos directo al login.
| Si ya está autenticado, Laravel lo redirige al dashboard.
|
*/
Route::get('/', function () {
    return redirect()->route('login'); // 🔹 Redirige directo al login
});
