<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HistoricalRecordController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\DB;

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

Route::get('/instalar-ml-hotel', function () {

    DB::statement("CREATE TABLE IF NOT EXISTS cache (
        `key` varchar(255) NOT NULL,
        `value` mediumtext NOT NULL,
        `expiration` int(11) NOT NULL,
        PRIMARY KEY (`key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    DB::statement("CREATE TABLE IF NOT EXISTS cache_locks (
        `key` varchar(255) NOT NULL,
        `owner` varchar(255) NOT NULL,
        `expiration` int(11) NOT NULL,
        PRIMARY KEY (`key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    DB::statement("CREATE TABLE IF NOT EXISTS failed_jobs (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        uuid varchar(255) NOT NULL,
        connection text NOT NULL,
        queue text NOT NULL,
        payload longtext NOT NULL,
        exception longtext NOT NULL,
        failed_at timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (id),
        UNIQUE KEY failed_jobs_uuid_unique (uuid)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    DB::statement("CREATE TABLE IF NOT EXISTS users (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        address varchar(255) NOT NULL,
        email varchar(255) NOT NULL UNIQUE,
        email_verified_at timestamp NULL DEFAULT NULL,
        password varchar(255) NOT NULL,
        rooms_number int(11) NOT NULL DEFAULT 1,
        remember_token varchar(100) DEFAULT NULL,
        created_at timestamp NULL DEFAULT NULL,
        updated_at timestamp NULL DEFAULT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    DB::statement("CREATE TABLE IF NOT EXISTS historical_records (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id bigint(20) UNSIGNED NOT NULL,
        date date NOT NULL,
        clima int(11) DEFAULT NULL,
        afluencia_turistica int(11) DEFAULT NULL,
        num_reservas int(11) DEFAULT NULL,
        porcentaje_ocupacion decimal(5,2) DEFAULT NULL,
        dia_festivo tinyint(1) NOT NULL DEFAULT 0,
        meta longtext DEFAULT NULL,
        created_at timestamp NULL DEFAULT NULL,
        updated_at timestamp NULL DEFAULT NULL,
        PRIMARY KEY (id),
        CONSTRAINT historical_records_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    DB::statement("CREATE TABLE IF NOT EXISTS predictions (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id bigint(20) UNSIGNED NOT NULL,
        date date NOT NULL,
        afluencia_turistica int(11) DEFAULT NULL,
        num_reservas int(11) DEFAULT NULL,
        porcentaje_ocupacion decimal(5,2) DEFAULT NULL,
        clima int(11) DEFAULT NULL,
        dia_festivo tinyint(1) NOT NULL DEFAULT 0,
        model_version varchar(255) NOT NULL DEFAULT 'v1.0',
        created_at timestamp NULL DEFAULT NULL,
        updated_at timestamp NULL DEFAULT NULL,
        PRIMARY KEY (id),
        CONSTRAINT predictions_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    return "✅ Base de datos instalada correctamente. Ahora revisa en Workbench.";
});