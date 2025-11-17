@extends('layouts.app')

@section('content')
<div class="w-full px-12 py-12">
    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-[#c9c5c4]">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-[#7a86a1] to-[#5c677f] text-white text-center py-6">
            <h2 class="text-3xl font-bold flex items-center justify-center gap-2">
                📊 Registro de Datos Históricos Turísticos
            </h2>
        </div>

        {{-- Mensajes --}}
        <div class="p-8">
            @if(session('success'))
                <div class="mb-6 flex items-center justify-between bg-[#e8f7ec] border border-[#b2dfdb] text-[#2e7d32] px-6 py-4 rounded-lg shadow">
                    <span>✅ {{ session('success') }}</span>
                    <button type="button" class="text-[#2e7d32] hover:text-[#1b5e20] text-lg font-bold" onclick="this.parentElement.remove()">
                        ✖
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-[#fef2f2] border border-[#f5c2c7] text-[#b91c1c] px-6 py-4 rounded-lg shadow">
                    <ul class="list-disc pl-5 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>⚠️ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Contenedor horizontal --}}
        <div class="flex flex-col lg:flex-row gap-10 px-12 pb-12">

            {{-- FORMULARIO MANUAL --}}
            <div class="lg:w-1/2 bg-[#f5f2f1] rounded-xl p-8 shadow-md border border-[#c9c5c4]">
                <h3 class="text-2xl font-semibold text-[#5c677f] mb-8">📝 Registro Manual</h3>

                <form action="{{ route('historical_records.store') }}" method="POST" class="grid grid-cols-2 gap-6">
                    @csrf

                    {{-- Fecha --}}
                    <div>
                        <label class="block font-semibold text-[#5c677f] mb-1">📅 Fecha</label>
                        <input type="date" name="date" value="{{ old('date') }}" required
                            class="w-full rounded-lg border border-[#c9c5c4] p-3 bg-white shadow-sm focus:ring-2 focus:ring-[#7a86a1]">
                    </div>

                    {{-- Clima --}}
                    <div>
                        <label class="block font-semibold text-[#5c677f] mb-1">⛅ Clima</label>
                        <select name="clima" required
                            class="w-full rounded-lg border border-[#c9c5c4] p-3 bg-white shadow-sm focus:ring-2 focus:ring-[#7a86a1]">
                            <option value="">-- Seleccionar --</option>
                            <option value="1" {{ old('clima')==1 ? 'selected':'' }}>Soleado</option>
                            <option value="2" {{ old('clima')==2 ? 'selected':'' }}>Nublado</option>
                            <option value="3" {{ old('clima')==3 ? 'selected':'' }}>Lluvioso</option>
                            <option value="4" {{ old('clima')==4 ? 'selected':'' }}>Caluroso</option>
                        </select>
                    </div>

                    {{-- Afluencia Turística --}}
                    <div>
                        <label class="block font-semibold text-[#5c677f] mb-1">👥 Afluencia Turística</label>
                        <input type="number" name="afluencia_turistica" value="{{ old('afluencia_turistica') }}" min="0" required
                            placeholder="Ej: 150" class="w-full rounded-lg border border-[#c9c5c4] p-3 bg-white shadow-sm focus:ring-2 focus:ring-[#7a86a1]">
                    </div>

                    {{-- Número de Reservas --}}
                    <div>
                        <label class="block font-semibold text-[#5c677f] mb-1">🏨 N° de Reservas</label>
                        <input type="number" name="num_reservas" value="{{ old('num_reservas') }}" min="0" required
                            placeholder="Ej: 35" class="w-full rounded-lg border border-[#c9c5c4] p-3 bg-white shadow-sm focus:ring-2 focus:ring-[#7a86a1]">
                    </div>

                    {{-- Porcentaje Ocupación --}}
                    <div>
                        <label class="block font-semibold text-[#5c677f] mb-1">% Ocupación</label>
                        <input type="number" step="0.01" name="porcentaje_ocupacion" value="{{ old('porcentaje_ocupacion') }}" min="0" max="100" required
                            placeholder="Ej: 75.5" class="w-full rounded-lg border border-[#c9c5c4] p-3 bg-white shadow-sm focus:ring-2 focus:ring-[#7a86a1]">
                    </div>

                    {{-- Día Festivo --}}
                    <div>
                        <label class="block font-semibold text-[#5c677f] mb-1">🎉 Día Festivo</label>
                        <select name="dia_festivo" required
                            class="w-full rounded-lg border border-[#c9c5c4] p-3 bg-white shadow-sm focus:ring-2 focus:ring-[#7a86a1]">
                            <option value="0" {{ old('dia_festivo')==0 ? 'selected':'' }}>No festivo</option>
                            <option value="1" {{ old('dia_festivo')==1 ? 'selected':'' }}>Festivo</option>
                        </select>
                    </div>

                    <div class="col-span-2 flex justify-end">
                        <button type="submit" class="bg-[#e57373] hover:bg-[#ef5350] text-white px-8 py-3 rounded-lg shadow-md transition">
                            💾 Guardar Registro
                        </button>
                    </div>
                </form>
            </div>

            {{-- FORMULARIO EXCEL --}}
            <div class="lg:w-1/2 bg-[#f5f2f1] rounded-xl p-8 shadow-md border border-[#c9c5c4]">
                <h3 class="text-2xl font-semibold text-[#5c677f] mb-8">📂 Registro Masivo desde Excel</h3>

                <form action="{{ route('historical_records.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block font-semibold text-[#5c677f] mb-1">
                            Selecciona archivo Excel (.xlsx / .xls / .csv)
                        </label>
                        <input type="file" name="file" accept=".xlsx,.xls,.csv"
                            class="w-full rounded-lg border border-[#c9c5c4] p-3 bg-white shadow-sm cursor-pointer focus:ring-2 focus:ring-[#7a86a1]">
                        <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                            <strong>Formato esperado:</strong><br>
                            Fecha | Clima | Afluencia Turística | N° Reservas | % Ocupación | Día Festivo<br>
                            (ejemplo: <code>01/01/2025 | 2 | 152 | 40 | 58.3 | 1</code>)
                        </p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-[#e57373] hover:bg-[#ef5350] text-white px-8 py-3 rounded-lg shadow-md transition">
                            ⬆ Importar Registros
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Botón volver --}}
        <div class="flex justify-start px-12 py-6 border-t border-[#c9c5c4] bg-[#f9f8f8]">
            <a href="{{ route('dashboard') }}" class="bg-[#5c677f] hover:bg-[#7a86a1] text-white font-semibold py-3 px-6 rounded-lg shadow-md transition">
                ⬅ Volver al Dashboard
            </a>
        </div>

    </div>
</div>
@endsection
