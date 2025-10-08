@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-[#c9c5c4]">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-[#7a86a1] to-[#5c677f] text-white text-center py-6">
            <h2 class="text-3xl font-bold flex items-center justify-center gap-2">
                📊 Resultados de Predicciones
            </h2>
        </div>

        <div class="p-8">
            {{-- Mensaje de éxito --}}
            @if(session('success'))
                <div class="mb-6 flex items-center justify-between bg-[#e8f7ec] border border-[#b2dfdb] text-[#2e7d32] px-6 py-4 rounded-lg text-base">
                    <span>✅ {{ session('success') }}</span>
                    <button type="button"
                        class="text-[#2e7d32] hover:text-[#1b5e20] text-lg font-bold"
                        onclick="this.parentElement.remove()"> ✖
                    </button>
                </div>
            @endif

            {{-- Tabla con predicciones --}}
            <div class="overflow-x-auto">
                <table class="min-w-full border border-[#c9c5c4] divide-y divide-[#c9c5c4] text-lg">
                    <thead class="bg-[#7a86a1] text-white">
                        <tr>
                            <th class="px-8 py-4 text-left font-semibold">Fecha</th>
                            <th class="px-8 py-4 text-left font-semibold">Afluencia Turística</th>
                            <th class="px-8 py-4 text-left font-semibold">N° Reservas</th>
                            <th class="px-8 py-4 text-left font-semibold">% Ocupación</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#c9c5c4]">
                        @forelse($predictions as $p)
                            <tr class="hover:bg-[#f5f2f1] transition">
                                <td class="px-8 py-4 text-gray-700 font-medium">{{ $p->fecha }}</td>
                                <td class="px-8 py-4 text-gray-700 font-medium">{{ $p->afluencia }}</td>
                                <td class="px-8 py-4 text-gray-700 font-medium">{{ $p->reservas }}</td>
                                <td class="px-8 py-4 text-gray-700 font-medium">{{ $p->ocupacion }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-6 text-center text-gray-500 text-lg">
                                    ⚠️ No hay predicciones registradas aún
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
