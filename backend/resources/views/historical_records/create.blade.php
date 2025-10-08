@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f5f2f1] flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-4xl bg-white shadow-2xl rounded-2xl overflow-hidden border border-[#ffffff]">
        
        {{-- Header --}}
        <div class="bg-gradient-to-r from-[#7a86a1] to-[#5c677f] text-white text-center py-5">
            <h2 class="text-2xl font-bold flex items-center justify-center gap-2">
                 Registro de Datos Históricos Turísticos
            </h2>
        </div>

        <div class="p-8 space-y-10">
            {{-- Mensaje de éxito --}}
            @if(session('success'))
                <div class="flex items-center justify-between bg-[#fdecea] border border-[#f5c2c0] text-[#e57373] px-4 py-3 rounded-lg text-sm animate-fadeIn">
                    <span>✅ {{ session('success') }}</span>
                    <button type="button" class="text-[#e57373] hover:text-[#c94a4a]" onclick="this.parentElement.remove()">
                        ✖
                    </button>
                </div>
            @endif

            {{-- Errores de validación --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded">
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORMULARIO MANUAL --}}
            <div class="border-b pb-6">
                <h3 class="text-xl font-semibold text-[#5c677f] mb-4">✍️ Registro Manual</h3>
                <form action="{{ route('historical_records.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Hotel --}}
                    <div>
                        <label class="block text-[#792727] font-semibold mb-2">
                             Hotel Registrado
                        </label>
                        <input type="text" value="{{ Auth::user()->name }}" 
                            class="w-full rounded-lg shadow-sm bg-gray-100 text-gray-700 p-3 focus:outline-none" readonly>
                        <p class="text-xs text-gray-500 mt-1">El registro se asociará automáticamente a este hotel.</p>
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <label for="date" class="block text-[#792727]  font-semibold mb-2">
                             Fecha del registro
                        </label>
                        <input type="date" id="date" name="date" value="{{ old('date') }}"
                            class="w-full rounded-lg shadow-sm bg-gray-50 p-3 
                                   focus:outline-none focus:ring-1 focus:ring-[#7a86a1] focus:border-[#7a86a1]" required>
                    </div>

                    {{-- Demanda --}}
                    <div>
                        <label for="demand_count" class="block text-[#792727]  font-semibold mb-2">
                             Demanda Turística
                        </label>
                        <input type="number" id="demand_count" name="demand_count" placeholder="Ejemplo: 120" value="{{ old('demand_count') }}"
                            class="w-full rounded-lg shadow-sm bg-gray-50 p-3 
                                   focus:outline-none focus:ring-1 focus:ring-[#e57373] focus:border-[#e57373]" required>
                    </div>

                    {{-- Meta --}}
                    <div>
                        <label for="meta" class="block text-[#792727]  font-semibold mb-2">
                             Notas / Meta (opcional)
                        </label>
                        <textarea id="meta" name="meta" rows="3" placeholder='{"notas":"temporada alta"}' 
                            class="w-full rounded-lg shadow-sm bg-gray-50 p-3 
                                   focus:outline-none focus:ring-1 focus:ring-[#c9c5c4] focus:border-[#c9c5c4]">{{ old('meta') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Puedes dejar texto libre o un JSON pequeño (ej. {"evento":"feria"}).</p>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end">
                        <button type="submit" class="bg-[#e57373] hover:bg-[#ef5350] text-white font-semibold py-3 px-6 rounded-lg shadow-md transition">
                             Guardar Registro
                        </button>
                    </div>
                </form>
            </div>

            {{-- FORMULARIO EXCEL --}}
            <div>
                <h3 class="text-xl font-semibold text-[#5c677f] mb-4">📂 Registro por carga Masiva desde Excel</h3>
                <form action="{{ route('historical_records.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-[#792727]  font-semibold mb-2">
                             Selecciona archivo Excel (.xlsx / .xls / .csv)
                        </label>
                        <input type="file" name="file" accept=".xlsx,.xls,.csv"
                            class="w-full rounded-lg shadow-sm bg-gray-50 p-3 cursor-pointer 
                                   focus:outline-none focus:ring-1 focus:ring-[#7a86a1] focus:border-[#7a86a1]">
                        <p class="text-xs text-gray-500 mt-1">Formato esperado: Columna A = Fecha, Columna B = Demanda, Columna C = Meta</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-[#e57373] hover:bg-[#ef5350] text-white font-semibold py-3 px-6 rounded-lg shadow-md transition">
                             Importar Registros
                        </button>
                    </div>
                </form>
            </div>

            {{-- Botón volver --}}
            <div class="flex justify-start pt-4 border-t">
                <a href="{{ route('dashboard') }}" class="bg-[#343b7e] hover:bg-[#428657] text-white font-semibold py-3 px-6 rounded-lg shadow-md transition">
                    ⬅ Volver al Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
