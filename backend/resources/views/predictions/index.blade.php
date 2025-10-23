@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-[#c9c5c4]">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-[#7a86a1] to-[#5c677f] text-white text-center py-6">
            <h2 class="text-3xl font-bold flex items-center justify-center gap-2">
                📊 Predicciones Generadas
            </h2>
        </div>

        <div class="p-8">
            {{-- Botón Volver --}}
            <div class="mb-4">
                <button onclick="history.back()" class="bg-[#7a86a1] hover:bg-[#5c677f] text-white px-4 py-2 rounded-lg font-medium transition">
                    ← Volver
                </button>
                <a href="{{ route('predictions.create') }}" class="ml-2 bg-[#5c677f] hover:bg-[#3e4659] text-white px-4 py-2 rounded-lg font-medium transition">
                    ➕ Nueva Predicción
                </a>
                <a href="{{ route('predictions.create') }}" class="ml-2 bg-[#5c677f] hover:bg-[#3e4659] text-white px-4 py-2 rounded-lg font-medium transition">
                    Descargar en PDF
                </a>
                <a href="{{ route('predictions.create') }}" class="ml-2 bg-[#5c677f] hover:bg-[#3e4659] text-white px-4 py-2 rounded-lg font-medium transition">
                    Descargar en Excel
                </a>
            </div>

            {{-- Mensajes --}}
            @if(session('success'))
                <div class="mb-6 flex items-center justify-between bg-[#e8f7ec] border border-[#b2dfdb] text-[#2e7d32] px-6 py-4 rounded-lg text-base">
                    <span>✅ {{ session('success') }}</span>
                    <button type="button" class="text-[#2e7d32] hover:text-[#1b5e20] text-lg font-bold" onclick="this.parentElement.remove()">✖</button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-[#fdecea] text-[#c62828] border border-[#f5c6cb] px-6 py-4 rounded-lg">
                    <strong>❌ Error:</strong> {{ $errors->first() }}
                </div>
            @endif

            {{-- Tabla --}}
            <div class="overflow-x-auto">
                <table class="min-w-full border border-[#c9c5c4] divide-y divide-[#c9c5c4] text-lg">
                    <thead class="bg-[#7a86a1] text-white">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold">Fecha</th>
                            <th class="px-6 py-4 text-left font-semibold">Clima</th>
                            <th class="px-6 py-4 text-left font-semibold">Afluencia Turística</th>
                            <th class="px-6 py-4 text-left font-semibold">N° Reservas</th>
                            <th class="px-6 py-4 text-left font-semibold">% Ocupación</th>
                            <th class="px-6 py-4 text-left font-semibold">Día Festivo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#c9c5c4]">
                        @forelse($predictions as $p)
                            <tr class="hover:bg-[#f5f2f1] transition">
                                <td class="px-6 py-4 text-gray-700 font-medium">
                                    {{ \Carbon\Carbon::parse($p->date)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-gray-700 font-medium">
                                    @switch($p->clima)
                                        @case(1) ☀️ Soleado @break
                                        @case(2) 🔥 Caluroso @break
                                        @case(3) ☁️ Nublado @break
                                        @case(4) 🌧️ Lluvioso @break
                                        @default ❓ Desconocido
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 text-gray-700 font-medium">{{ $p->afluencia_turistica ?? 0 }}</td>
                                <td class="px-6 py-4 text-gray-700 font-medium">{{ $p->num_reservas ?? 0 }}</td>
                                <td class="px-6 py-4 text-gray-700 font-medium">{{ number_format($p->porcentaje_ocupacion ?? 0, 2) }}%</td>
                                <td class="px-6 py-4 text-gray-700 font-medium">{{ $p->dia_festivo ? 'Sí' : 'No' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-500">
                                    No hay predicciones registradas aún.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pie --}}
            <div class="mt-8 text-right text-sm text-gray-500">
                <em>Mostrando {{ $predictions->count() }} predicción(es).</em>
            </div>
        </div>
    </div>
</div>
@endsection
