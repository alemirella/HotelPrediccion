<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HistoricalRecordController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\View;

View::addLocation(base_path('../Frontend/resources/views'));

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $predictions = PredictionController::getDashboardData();
        return view('dashboard', compact('predictions'));
    })->name('dashboard');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('historical_records', HistoricalRecordController::class);

    Route::post('/historical_records/import', [HistoricalRecordController::class, 'importExcel'])
        ->name('historical_records.import');

    Route::get('/predictions/create', [PredictionController::class, 'create'])->name('predictions.create');
    Route::post('/predictions', [PredictionController::class, 'store'])->name('predictions.store');
    Route::get('/predictions', [PredictionController::class, 'index'])->name('predictions.index');
    
    
    // ✅ EXTERNAL FACTORS ROUTES
    Route::get('/external_factors', [PredictionController::class, 'showExternalFactors'])
    ->name('external_factors.index');

    Route::post('/external-factors/analyze', [PredictionController::class, 'analyzeExternalFactors'])
    ->name('external_factors.analyze');



    // ✅ RUTA DE PRECIOS
    Route::get('/prices', [PredictionController::class, 'showPrices'])->name('prices.index');
    Route::post('/prices/recommended', [PredictionController::class, 'getRecommendedPrice'])->name('prices.recommended');


    // ✅ NUEVA RUTA PARA INSIGHTS GERENCIA
    Route::get('/insights', [PredictionController::class, 'showInsights'])->name('insights.index');

    Route::get('/predictions/export/pdf', [PredictionController::class, 'exportPDF'])->name('predictions.exportPDF');
    Route::get('/predictions/export/excel', [PredictionController::class, 'exportExcel'])->name('predictions.exportExcel');
});

Route::get('/', function () {
    return redirect()->route('predictions.index');
});
