<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prediction;
use App\Models\HistoricalRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PredictionController extends Controller
{
    /**
     * Mostrar tabla de predicciones
     */
    public function index(Request $request)
    {
        $start = $request->get('start');
        $end   = $request->get('end');

        $query = Prediction::where('user_id', Auth::id())
            ->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m-%d") as fecha'),
                DB::raw('predicted_count as afluencia'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(input_features, "$.n_reservas")) as reservas'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(input_features, "$.ocupacion")) as ocupacion')
            )
            ->orderBy('fecha', 'desc');

        // ✅ Si viene rango de fechas (cuando se predice), filtramos
        if ($start && $end) {
            $query->whereBetween('date', [$start, $end]);
        }

        $predictions = $query->get();

        return view('predictions.index', compact('predictions', 'start', 'end'));
    }

    /**
     * Mostrar formulario para predecir
     */
    public function create()
    {
        return view('predictions.create');
    }

    /**
     * Guardar predicción y mostrarla en tabla
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'days' => 'required|in:7,15,30',
        ]);

        $startDate = $request->date;
        $endDate   = date('Y-m-d', strtotime($startDate . ' + ' . ($request->days - 1) . ' days'));

        try {
            $flaskUrl = 'http://127.0.0.1:5000/predict';

            $response = Http::post($flaskUrl, [
                'fecha' => $startDate
            ]);

            if (!$response->successful()) {
                return back()->withErrors([
                    'api_error' => 'La API Flask no respondió correctamente (status ' . $response->status() . ')'
                ]);
            }

            $predictionData = $response->json();

            // ✅ Guardar predicción
            Prediction::create([
                'user_id'         => Auth::id(),
                'date'            => $startDate,
                'predicted_count' => $predictionData['prediccion']['Afluencia Turistica'],
                'model_version'   => 'v1.0',
                'input_features'  => [
                    'days'       => $request->days,
                    'end_date'   => $endDate,
                    'n_reservas' => $predictionData['prediccion']['N# reservas'],
                    'ocupacion'  => $predictionData['prediccion']['% ocupacion']
                ]
            ]);

            // ✅ Redirigir a la tabla mostrando SOLO los datos de esa predicción
            return redirect()->route('predictions.index', [
                'start' => $startDate,
                'end'   => $endDate
            ])->with('success', '✅ Predicción generada correctamente.');

        } catch (\Exception $e) {
            return back()->withErrors([
                'api_error' => '❌ Error al conectar con el servicio Flask: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Exportar datos históricos a CSV
     */
    public function export()
    {
        $fileName = "historical_records.csv";

        $records = HistoricalRecord::where('user_id', Auth::id())->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['date', 'demand_count', 'meta'];

        $callback = function () use ($records, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($records as $record) {
                fputcsv($file, [
                    $record->date,
                    $record->demand_count,
                    json_encode($record->meta)
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
