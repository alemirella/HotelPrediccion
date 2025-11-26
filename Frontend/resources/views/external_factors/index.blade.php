@extends('layouts.app')

@section('content')
<div class="p-8">

    <!-- TÍTULO Y BOTÓN VOLVER -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h2 class="text-2xl font-bold text-[#7a86a1] mb-4 md:mb-0">📊 Análisis de Factores Externos</h2>
    </div>

    <!-- FILTROS -->
    <div class="bg-white shadow-lg rounded-2xl p-6 mb-8">
        <form method="POST" action="{{ route('external_factors.analyze') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-semibold mb-1 block">Clima</label>
                    <select name="clima" class="border border-gray-300 rounded-lg w-full p-3 focus:outline-none focus:ring-2 focus:ring-[#7a86a1]">
                        @php
                            $climas = ['nublado','soleado','caluroso','lluvioso'];
                        @endphp
                        @foreach($climas as $c)
                            <option value="{{ $c }}" {{ isset($filters['clima']) && $filters['clima']==$c ? 'selected' : '' }}>
                                {{ ucfirst($c) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold mb-1 block">Día festivo</label>
                    <select name="dia_festivo" class="border border-gray-300 rounded-lg w-full p-3 focus:outline-none focus:ring-2 focus:ring-[#7a86a1]">
                        <option value="0" {{ isset($filters['dia_festivo']) && !$filters['dia_festivo'] ? 'selected' : '' }}>No</option>
                        <option value="1" {{ isset($filters['dia_festivo']) && $filters['dia_festivo'] ? 'selected' : '' }}>Sí</option>
                    </select>
                </div>
            </div>

            <!-- BOTONES CENTRADOS DENTRO DEL CUADRO -->
            <div class="flex justify-center gap-6 pt-6 border-t border-[#e0e0e0] mt-6">
                <a href="{{ route('dashboard') }}" 
                class="bg-[#7a86a1] text-white px-8 py-3 rounded-xl shadow hover:bg-[#6d7595] transition">
                    Volver
                </a>
                <button type="submit" 
                class="bg-[#7a86a1] text-white px-8 py-3 rounded-xl shadow hover:bg-[#6d7595] transition">
                    Analizar
                </button>
            </div>
        </form>
    </div>


    @if(isset($analysisResult))
    <!-- RESULTADOS -->
    <div class="bg-white shadow-lg rounded-2xl p-6 mb-8">
        <h3 class="text-lg font-bold mb-6 text-center text-[#7a86a1]">
            Impacto del clima "{{ $filters['clima'] }}" y {{ $filters['dia_festivo'] ? 'día festivo ✅' : 'día normal ❌' }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 shadow-sm">
                <p class="text-gray-500 mb-2">Afluencia</p>
                <p class="text-xl font-bold text-[#7a86a1]">{{ $analysisResult['afluencia_turistica'] }}</p>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 shadow-sm">
                <p class="text-gray-500 mb-2">Reservas</p>
                <p class="text-xl font-bold text-[#7a86a1]">{{ $analysisResult['num_reservas'] }}</p>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 shadow-sm">
                <p class="text-gray-500 mb-2">Ocupación %</p>
                <p class="text-xl font-bold text-[#7a86a1]">{{ $analysisResult['porcentaje_ocupacion'] }}%</p>
            </div>
        </div>
    </div>

    <!-- GRÁFICOS -->
    <div class="bg-white shadow-lg rounded-2xl p-6 max-w-4xl mx-auto">
        <h3 class="text-lg font-bold mb-6 text-center text-[#7a86a1]">Visualización del impacto</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 justify-items-center">
            <div class="w-full max-w-sm">
                <canvas id="chartAfluencia"></canvas>
            </div>
            <div class="w-full max-w-sm">
                <canvas id="chartReservas"></canvas>
            </div>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@isset($analysisResult)
<script>
const result = {!! json_encode($analysisResult) !!};

// AFLUENCIA
new Chart(document.getElementById('chartAfluencia').getContext('2d'), {
    type: 'bar',
    data: {
        labels: ['Afluencia Turística'],
        datasets: [{
            label: 'Turistas',
            data: [result.afluencia_turistica],
            backgroundColor: '#7a86a1'
        }]
    },
    options: { responsive: true, maintainAspectRatio: true }
});

// RESERVAS Y OCUPACIÓN
new Chart(document.getElementById('chartReservas').getContext('2d'), {
    type: 'line',
    data: {
        labels: ['Reservas', '% Ocupación'],
        datasets: [{
            label: 'Impacto',
            data: [result.num_reservas, result.porcentaje_ocupacion],
            borderColor: '#e57373',
            backgroundColor: 'rgba(229,115,115,0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: { responsive: true, maintainAspectRatio: true }
});
</script>
@endisset
@endsection
