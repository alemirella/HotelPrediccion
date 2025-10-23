<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prediction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

class PredictionController extends Controller
{
    /**
     * Mostrar todas las predicciones del usuario autenticado.
     */
    public function index()
    {
        $predictions = Prediction::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->get();

        return view('predictions.index', compact('predictions'));
    }

    /**
     * Mostrar formulario para crear nueva predicción.
     */
    public function create()
    {
        return view('predictions.create');
    }

    /**
     * Enviar fecha a Flask, recibir resultado y guardar predicción.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'days' => 'required|integer|min:1|max:365'
        ]);

        $flaskUrl = 'http://127.0.0.1:5000/predict';

        try {
            // Enviar solicitud POST a Flask
            $response = Http::post($flaskUrl, [
                'fecha' => $request->date,
                'dias'  => $request->days
            ]);

            // Validar respuesta
            if (!$response->successful()) {
                return back()->withErrors([
                    'api_error' => 'Error al contactar con Flask (' . $response->status() . ')'
                ]);
            }

            $json = $response->json();

            // Flask devuelve: { "fecha":"YYYY-MM-DD", "prediccion": { ... } }
            $predData = $json['prediccion'] ?? null;

            if (!$predData) {
                // por seguridad, intenta usar todo el json como predicción
                $predData = $json;
            }

            // Si la predicción es un array de predicciones (multi-día), normalizarlo
            $predList = [];
            if (is_array($predData) && array_keys($predData) === range(0, count($predData) - 1)) {
                // es una lista indexada
                $predList = $predData;
            } else {
                // único objeto
                $predList[] = $predData;
            }

            foreach ($predList as $data) {
                // Normalizar claves: aceptamos tanto snake_case como nombres variantes
                $fecha = $data['fecha'] ?? $data['Fecha'] ?? $request->date;
                $afluencia = $data['afluencia_turistica'] ?? $data['Afluencia Turistica'] ?? ($data['afluencia'] ?? 0);
                $num_reservas = $data['num_reservas'] ?? $data['Num Reservas'] ?? $data['N# reservas'] ?? 0;
                $porcentaje = $data['porcentaje_ocupacion'] ?? $data['% ocupacion'] ?? $data['Porcentaje Ocupacion'] ?? 0;
                $clima = $data['clima'] ?? $data['Clima'] ?? null;
                $dia_festivo = $data['dia_festivo'] ?? $data['Dia Festivo'] ?? false;

                // Asegurar formato de fecha
                try {
                    $dateParsed = Carbon::parse($fecha)->format('Y-m-d');
                } catch (\Exception $e) {
                    $dateParsed = Carbon::parse($request->date)->format('Y-m-d');
                }

                Prediction::create([
                    'user_id'              => Auth::id(),
                    'date'                 => $dateParsed,
                    'afluencia_turistica'  => (int) round($afluencia),
                    'num_reservas'         => (int) round($num_reservas),
                    'porcentaje_ocupacion' => (float) $porcentaje,
                    'clima'                => is_null($clima) ? null : (int) round($clima),
                    'dia_festivo'          => (bool) $dia_festivo,
                    'model_version'        => 'v1.0'
                ]);
            }

            return redirect()->route('predictions.index')
                ->with('success', '✅ Predicción generada y guardada correctamente.');

        } catch (\Exception $e) {
            return back()->withErrors([
                'api_error' => '❌ Error al conectar con Flask: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Retornar datos listos para graficar.
     */
    public static function getDashboardData()
    {
        return Prediction::where('user_id', Auth::id())
            ->orderBy('date', 'asc')
            ->get([
                'date',
                'afluencia_turistica',
                'num_reservas',
                'porcentaje_ocupacion',
                'clima',
                'dia_festivo'
            ]);
    }
}
