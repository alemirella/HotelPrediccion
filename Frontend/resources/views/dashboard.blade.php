@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#f5f2f1]">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#7a86a1] text-white flex flex-col">
        <div class="p-6 text-center text-2xl font-bold border-b border-[#6a7894]">
            Dashboard
        </div>
        <nav class="flex-1 p-4 space-y-4">
            <a href="#pmv1" class="block px-4 py-2 rounded-lg hover:bg-[#6a7894] transition">Predicción de Afluencia Turística</a>
            <a href="#pmv2" class="block px-4 py-2 rounded-lg hover:bg-[#6a7894] transition">Visualización de Tendencias</a>
            <a href="#pmv3" class="block px-4 py-2 rounded-lg hover:bg-[#6a7894] transition">Factores Externos</a>
        </nav>
        <div class="p-4 border-t border-[#6a7894]">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-white text-[#7a86a1] px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- Contenedor principal -->
    <main class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="w-full bg-white shadow p-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-[#7a86a1]">Predicción de Afluencia Turística</h1>
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:underline">Volver al Login</a>
        </header>

        <!-- Contenido -->
        <section class="flex-1 p-8 overflow-y-auto">
            <!-- KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white shadow rounded-xl p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-700">Predicciones</h3>
                    <p class="text-3xl font-bold text-[#e57373]">12</p>
                    <span class="text-sm text-gray-500">Esta semana</span>
                </div>
                <div class="bg-white shadow rounded-xl p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-700">Registros históricos</h3>
                    <p class="text-3xl font-bold text-[#e57373]">45</p>
                    <span class="text-sm text-gray-500">Total acumulado</span>
                </div>
                <div class="bg-white shadow rounded-xl p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-700">Hoteles activos</h3>
                    <p class="text-3xl font-bold text-[#e57373]">7</p>
                    <span class="text-sm text-gray-500">Registrados</span>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white shadow rounded-xl p-6 mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex gap-4">
                    <select class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a86a1]">
                        <option>2025</option>
                        <option>2024</option>
                        <option>2023</option>
                    </select>
                    <select class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a86a1]">
                        <option>Enero - Junio</option>
                        <option>Julio - Diciembre</option>
                    </select>
                </div>
                <button class="bg-[#7a86a1] text-white px-4 py-2 rounded-lg hover:bg-[#5c677f] transition">
                    Aplicar Filtros
                </button>
            </div>

            <!-- Gráficos principales -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <!-- Línea -->
                <div class="bg-white shadow rounded-xl p-6">
                    <h2 class="text-xl font-bold text-[#7a86a1] mb-4">Tendencia de Afluencia Turística</h2>
                    <canvas id="tourismChart"></canvas>
                </div>
                <!-- Barras -->
                <div class="bg-white shadow rounded-xl p-6">
                    <h2 class="text-xl font-bold text-[#7a86a1] mb-4">Comparativa por Hoteles</h2>
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            <!-- PMV1 -->
            <div id="pmv1" class="mb-12">
                <h2 class="text-2xl font-bold text-[#7a86a1] mb-6">PMV1: Funcionalidades</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col justify-between hover:shadow-xl transition border-t-4 border-[#e57373]">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Registro de Datos Históricos</h3>
                        <p class="text-sm text-gray-600 mb-4">Permite ingresar datos de hoteles y demanda turística.</p>
                        <a href="{{ route('historical_records.create') }}" class="bg-[#e57373] text-white px-4 py-2 rounded-lg text-center hover:bg-[#ef5350] transition">Ir a Registro</a>
                    </div>
                    <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col justify-between hover:shadow-xl transition border-t-4 border-[#7a86a1]">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Predecir Afluencia</h3>
                        <p class="text-sm text-gray-600 mb-4">Genera predicciones de la afluencia turística según fechas.</p>
                        <a href="{{ route('predictions.create') }}" class="bg-[#7a86a1] text-white px-4 py-2 rounded-lg text-center hover:bg-[#5c677f] transition">Ir a Predicciones</a>
                    </div>
                    <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col justify-between hover:shadow-xl transition border-t-4 border-[#c9c5c4]">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Tablas de Demanda</h3>
                        <p class="text-sm text-gray-600 mb-4">Consulta tablas históricas y predicciones turísticas.</p>
                        <a href="{{ route('predictions.index') }}" class="bg-[#c9c5c4] text-white px-4 py-2 rounded-lg text-center hover:bg-[#a6a1a0] transition">Ver Tablas</a>
                    </div>
                </div>
            </div>

            <!-- 🟣 PMV2 CON DATOS REALES -->
            <div id="pmv2" class="mb-12">
                <h2 class="text-2xl font-bold text-[#7a86a1] mb-6">PMV2: Visualización de Tendencias</h2>

                @if(count($predictions) > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                        <div class="bg-white shadow rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Evolución de la Afluencia Turística</h3>
                            <canvas id="trendChart"></canvas>
                        </div>

                        <div class="bg-white shadow rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Afluencia Promedio según Clima</h3>
                            <canvas id="weatherChart"></canvas>
                        </div>

                        <div class="bg-white shadow rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Impacto de los Días Festivos</h3>
                            <canvas id="holidayChart"></canvas>
                        </div>

                        <div class="bg-white shadow rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Relación entre Reservas y Ocupación</h3>
                            <canvas id="correlationChart"></canvas>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500">⚠️ No hay datos suficientes para mostrar gráficos.</p>
                @endif
            </div>

            <!-- PMV3 -->
            <div id="pmv3">
                <h2 class="text-2xl font-bold text-[#7a86a1] mb-6">PMV3: Factores Externos</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col justify-between hover:shadow-xl transition border-t-4 border-[#e57373]">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Módulo de análisis externo</h3>
                        <p class="text-sm text-gray-600 mb-4">Revisa la influencia del clima, temporadas y eventos.</p>
                        <a href="{{ route('external_factors.index') }}" class="bg-[#e57373] text-white px-4 py-2 rounded-lg text-center hover:bg-[#ef5350] transition">Ver Factores Externos</a>
                    </div>
                    <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col justify-between hover:shadow-xl transition border-t-4 border-[#7a86a1]">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Precios dinámicos</h3>
                        <p class="text-sm text-gray-600 mb-4">Recomendaciones automáticas de precios dinámicos.</p>
                        <a href="{{ route('prices.index') }}" class="bg-[#7a86a1] text-white px-4 py-2 rounded-lg text-center hover:bg-[#5c677f] transition">Ver Precios</a>
                    </div>
                    <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col justify-between hover:shadow-xl transition border-t-4 border-[#c9c5c4]">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Panel de insights</h3>
                        <p class="text-sm text-gray-600 mb-4">Panel de insights estratégicos para la gerencia.</p>
                        <a href="{{ route('insights.index') }}" class="bg-[#c9c5c4] text-white px-4 py-2 rounded-lg text-center hover:bg-[#a6a1a0] transition">Ver Panel</a>
                    </div>
                </div>

                <!--   AQUI VA PRECIOS -->

            </div>
            <!-- fin PMV3 -->

        </section>
    </main>
</div>

<!-- Chart.js global -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Scripts generales -->
<script>
    // Gráfico de línea
    new Chart(document.getElementById('tourismChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
            datasets: [{
                label: 'Afluencia Turística',
                data: [120, 150, 180, 200, 170, 220],
                borderColor: '#7a86a1',
                backgroundColor: 'rgba(122, 134, 161, 0.2)',
                fill: true,
                tension: 0.3
            }]
        }
    });

    // Gráfico de barras
    new Chart(document.getElementById('barChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Hotel A', 'Hotel B', 'Hotel C', 'Hotel D'],
            datasets: [{
                label: 'Turistas',
                data: [80, 120, 60, 150],
                backgroundColor: ['#7a86a1', '#e57373', '#c9c5c4', '#5c677f']
            }]
        }
    });
</script>

<!-- 🟣 Scripts dinámicos del PMV2 -->
<script>
@if(count($predictions) > 0)
// === 1️⃣ Tendencia temporal ===
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($predictions->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!},
        datasets: [{
            label: 'Afluencia Turística',
            data: {!! json_encode($predictions->pluck('afluencia_turistica')) !!},
            borderColor: '#7a86a1',
            backgroundColor: 'rgba(122,134,161,0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: { responsive: true }
});

// === 2️⃣ Clima vs Afluencia ===
new Chart(document.getElementById('weatherChart'), {
    type: 'bar',
    data: {
        labels: ['Soleado', 'Caluroso', 'Nublado', 'Lluvioso'],
        datasets: [{
            label: 'Promedio de Afluencia',
            data: [
                {{ round($predictions->where('clima',1)->avg('afluencia_turistica') ?? 0, 2) }},
                {{ round($predictions->where('clima',2)->avg('afluencia_turistica') ?? 0, 2) }},
                {{ round($predictions->where('clima',3)->avg('afluencia_turistica') ?? 0, 2) }},
                {{ round($predictions->where('clima',4)->avg('afluencia_turistica') ?? 0, 2) }}
            ],
            backgroundColor: ['#fdd835', '#ffb300', '#90a4ae', '#4fc3f7']
        }]
    }
});

// === 3️⃣ Días festivos ===
new Chart(document.getElementById('holidayChart'), {
    type: 'doughnut',
    data: {
        labels: ['Día Festivo', 'Normal'],
        datasets: [{
            data: [
                {{ round($predictions->where('dia_festivo', true)->avg('afluencia_turistica') ?? 0, 2) }},
                {{ round($predictions->where('dia_festivo', false)->avg('afluencia_turistica') ?? 0, 2) }}
            ],
            backgroundColor: ['#e57373', '#c9c5c4']
        }]
    }
});

// === 4️⃣ Correlación Reservas vs Ocupación ===
new Chart(document.getElementById('correlationChart'), {
    type: 'scatter',
    data: {
        datasets: [{
            label: 'Reservas vs Ocupación',
            data: {!! json_encode($predictions->map(fn($p) => ['x' => $p->n_reservas, 'y' => $p->porcentaje_ocupacion])) !!},
            backgroundColor: '#7a86a1'
        }]
    }
});
@endif
</script>
@endsection
