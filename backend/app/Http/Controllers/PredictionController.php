<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prediction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon; 
use Spatie\SimpleExcel\SimpleExcelWriter;
use Barryvdh\DomPDF\Facade\Pdf;

class PredictionController extends Controller
{
    public function index()
    {
        $predictions = Prediction::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->get();

        return view('predictions.index', compact('predictions'));
    }

    public function create()
    {
        return view('predictions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'days' => 'required|integer|min:1|max:365'
        ]);

        $flaskUrl = 'http://127.0.0.1:5000/predict';

        try {
            $response = Http::post($flaskUrl, [
                'fecha' => $request->date,
                'dias'  => $request->days
            ]);

            if (!$response->successful()) {
                return back()->withErrors([
                    'api_error' => 'Error al contactar con Flask (' . $response->status() . ')'
                ]);
            }

            $json = $response->json();
            $predData = $json['prediccion'] ?? $json;

            $predList = [];
            if (is_array($predData) && array_keys($predData) === range(0, count($predData) - 1)) {
                $predList = $predData;
            } else {
                $predList[] = $predData;
            }

            foreach ($predList as $data) {
                $fecha = $data['fecha'] ?? $request->date;
                $num_reservas = $data['num_reservas'] ?? 0;
                $porcentaje = $data['porcentaje_ocupacion'] ?? 0;
                $clima = $data['clima'] ?? null;
                $dia_festivo = $data['dia_festivo'] ?? false;
                $afluencia = $data['afluencia_turistica'] ?? 0;

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

    // EXTERNAL FACTORS
    // Mostrar vista inicial con predicciones existentes
    public function showExternalFactors()
    {
        // Traemos todas las predicciones del usuario
        $predictions = Prediction::where('user_id', Auth::id())->get();

        return view('external_factors.index', compact('predictions'));
    }

    public function analyzeExternalFactors(Request $request)
    {
        $request->validate([
            'clima' => 'required|string',
            'dia_festivo' => 'required|boolean'
        ]);

        $predictions = Prediction::where('user_id', Auth::id())->get();

        if ($predictions->isEmpty()) {
            return back()->withErrors(['api_error' => 'No hay predicciones disponibles.']);
        }

        // Mapeo de clima
        $climaMap = ['soleado'=>1,'caluroso'=>2,'nublado'=>3,'lluvioso'=>4];
        $climaValue = $climaMap[strtolower($request->clima)] ?? null;

        $diaFestivo = $request->dia_festivo ? 1 : 0;

        // Filtramos predicciones
        $filtered = $predictions->filter(function($p) use ($climaValue, $diaFestivo) {
            $climaMatch = is_null($climaValue) ? true : (int)$p->clima === (int)$climaValue;
            $festivoMatch = (int)$p->dia_festivo === (int)$diaFestivo;
            return $climaMatch && $festivoMatch;
        });

        // Si no hay predicciones filtradas, usamos todas para evitar ceros
        if ($filtered->isEmpty()) {
            $filtered = $predictions;
        }

        $analysisResult = [
            'afluencia_turistica' => round($filtered->avg('afluencia_turistica') ?? 0,2),
            'num_reservas' => round($filtered->avg('num_reservas') ?? 0,2),
            'porcentaje_ocupacion' => round($filtered->avg('porcentaje_ocupacion') ?? 0,2)
        ];

        return view('external_factors.index', [
            'predictions' => $predictions,
            'analysisResult' => $analysisResult,
            'filters' => [
                'clima' => $request->clima,
                'dia_festivo' => $request->dia_festivo
            ]
        ]);
    }


    // PRECIOS
    // PRECIOS
    public function showPrices()
    {
        // Podrías pasar predicciones existentes si quieres mostrar histórico
        return view('prices.index');
    }

    public function getRecommendedPrice(Request $request)
    {
        $request->validate([
            'precio_actual' => 'required|numeric',
            'ocupacion_hotel' => 'required|numeric',
            'ocupacion_zona' => 'required|numeric',
            'anticipacion_reserva' => 'required|numeric',
            'dia_semana' => 'required|string',
            'mes' => 'required|string',
            'tipo_habitacion' => 'required|string',
            'competencia_precio_promedio' => 'required|numeric',
            'evento_ciudad' => 'required|boolean',
            'clima' => 'required|string',
            'demanda_historica' => 'required|numeric',
            'feriado' => 'required|boolean',
        ]);

        $flaskUrl = "http://127.0.0.1:5001/predict-price";

        try {
            $response = Http::post($flaskUrl, $request->all());

            if (!$response->successful()) {
                return back()->withErrors(['api_error' => 'Error al contactar ML service']);
            }

            $precio = $response->json()['precio_recomendado'] ?? null;

            return view('prices.index', ['precio_recomendado' => $precio]);

        } catch (\Exception $e) {
            return back()->withErrors(['api_error' => 'Error al conectar con ML service: ' . $e->getMessage()]);
        }
    }


    // INSIGHTS
    public function showInsights()
    {
        $predictions = self::getDashboardData();
        return view('insights.index', compact('predictions'));
    }

    public function exportExcel()
    {
        $filePath = storage_path('app/predictions.xlsx');

        SimpleExcelWriter::create($filePath)
            ->addRows(
                Prediction::where('user_id', Auth::id())->get([
                    'date',
                    'clima',
                    'afluencia_turistica',
                    'num_reservas',
                    'porcentaje_ocupacion',
                    'dia_festivo'
                ])->toArray()
            );

        return response()->download($filePath);
    }

    public function exportPDF()
    {
        $predictions = Prediction::where('user_id', Auth::id())->get();
        $pdf = Pdf::loadView('pdf.predictions', compact('predictions'))->setPaper('a4', 'landscape');
        return $pdf->download('predicciones.pdf');
    }
}
