@extends('layouts.app')

@section('content')
<div class="p-8">

    <!-- Encabezado con botón volver -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h2 class="text-2xl font-bold text-[#7a86a1] mb-4 md:mb-0">📊 Insights Estratégicos</h2>
        <a href="{{ route('dashboard') }}" class="bg-[#7a86a1] text-white px-6 py-2 rounded-xl shadow hover:bg-[#6d7595] transition">
            ← Volver al Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Insight 1: Tendencia de Demanda -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Tendencia de Demanda</h3>
            <p class="text-sm text-gray-500 mb-3">Comportamiento histórico de reservas por mes.</p>
            <div class="h-40">
                <canvas id="chartDemanda"></canvas>
            </div>
        </div>

        <!-- Insight 2: Impacto de Factores Externos -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Impacto de Factores Externos</h3>
            <p class="text-sm text-gray-500 mb-3">Influencia del clima, feriados y afluencia.</p>
            <div class="h-40">
                <canvas id="chartFactores"></canvas>
            </div>
        </div>

        <!-- Insight 3: Recomendación de Precios -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Recomendación de Precios</h3>
            <p class="text-sm text-gray-500 mb-3">Sugerencias basadas en ocupación y demanda mensual.</p>
            <div class="h-40">
                <canvas id="chartPrecios"></canvas>
            </div>
        </div>

    </div>

    <!-- KPIs destacados -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
        <div class="bg-white shadow rounded-2xl p-6 text-center">
            <h4 class="text-sm font-medium text-gray-500 mb-2">Prom. Aflluencia</h4>
            <p class="text-2xl font-bold text-[#e57373]">
                {{ round($predictions->avg('afluencia_turistica') ?? 0, 2) }}
            </p>
        </div>
        <div class="bg-white shadow rounded-2xl p-6 text-center">
            <h4 class="text-sm font-medium text-gray-500 mb-2">Prom. Reservas</h4>
            <p class="text-2xl font-bold text-[#e57373]">
                {{ round($predictions->avg('num_reservas') ?? 0, 2) }}
            </p>
        </div>
        <div class="bg-white shadow rounded-2xl p-6 text-center">
            <h4 class="text-sm font-medium text-gray-500 mb-2">Prom. Ocupación (%)</h4>
            <p class="text-2xl font-bold text-[#e57373]">
                {{ round($predictions->avg('porcentaje_ocupacion') ?? 0, 2) }}
            </p>
        </div>
        <div class="bg-white shadow rounded-2xl p-6 text-center">
            <h4 class="text-sm font-medium text-gray-500 mb-2">Días Festivos</h4>
            <p class="text-2xl font-bold text-[#e57373]">
                {{ $predictions->where('dia_festivo', true)->count() }}
            </p>
        </div>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Agrupar predicciones por mes
    const rawData = {!! $predictions !!};

    const monthlyData = {};

    rawData.forEach(p => {
        const date = new Date(p.date);
        const monthKey = date.getFullYear() + '-' + String(date.getMonth()+1).padStart(2,'0');
        if (!monthlyData[monthKey]) {
            monthlyData[monthKey] = { afluencia: 0, reservas: 0, ocupacion: 0, count: 0 };
        }
        monthlyData[monthKey].afluencia += p.afluencia_turistica;
        monthlyData[monthKey].reservas += p.num_reservas;
        monthlyData[monthKey].ocupacion += p.porcentaje_ocupacion;
        monthlyData[monthKey].count += 1;
    });

    const labels = Object.keys(monthlyData).map(k => {
        const parts = k.split('-');
        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        return monthNames[parseInt(parts[1])-1] + ' ' + parts[0];
    });

    const afluenciaData = Object.values(monthlyData).map(v => (v.afluencia/v.count).toFixed(2));
    const reservasData = Object.values(monthlyData).map(v => (v.reservas/v.count).toFixed(2));
    const ocupacionData = Object.values(monthlyData).map(v => (v.ocupacion/v.count).toFixed(2));

    // Tendencia de Demanda
    new Chart(document.getElementById('chartDemanda'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                { label: 'N° Reservas', data: reservasData, borderColor:'#e57373', backgroundColor:'rgba(229,115,115,0.2)', tension:0.3 }
            ]
        },
        options: { responsive:true }
    });

    // Impacto Factores Externos
    new Chart(document.getElementById('chartFactores'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                { label: 'Afluencia Turística', data: afluenciaData, borderColor:'#7a86a1', backgroundColor:'rgba(122,134,161,0.2)', tension:0.3 }
            ]
        },
        options: { responsive:true }
    });

    // Recomendación de Precios
    new Chart(document.getElementById('chartPrecios'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                { label: 'Porcentaje Ocupación', data: ocupacionData, borderColor:'#ffb74d', backgroundColor:'rgba(255,183,77,0.2)', tension:0.3 }
            ]
        },
        options: { responsive:true }
    });
</script>
@endsection
