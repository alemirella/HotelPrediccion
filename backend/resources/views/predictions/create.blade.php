@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4 py-10">
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-[#c9c5c4]">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-[#7a86a1] to-[#5c677f] text-white text-center py-5">
            <h2 class="text-2xl font-semibold flex items-center justify-center gap-2">
                🔮 Predecir Afluencia Turística
            </h2>
        </div>

        <div class="p-6 space-y-6">
            {{-- Errores --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <strong>Errores:</strong>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formulario --}}
            <form action="{{ route('predictions.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Selección de rango --}}
                <div>
                    <label for="days" class="block text-[#5c677f] font-medium mb-2">
                        📌 Selecciona rango de días
                    </label>
                    <select id="days" name="days"
                        class="w-full border rounded-lg shadow-sm p-3 focus:outline-none focus:ring-1 focus:ring-[#e57373] focus:border-[#e57373]"
                        required>
                        <option value="">-- Selecciona --</option>
                        <option value="7">7 días</option>
                        <option value="15">15 días</option>
                        <option value="30">30 días</option>
                    </select>
                </div>

                {{-- Fecha --}}
                <div>
                    <label for="date" class="block text-[#5c677f] font-medium mb-2">
                        📅 Fecha de inicio
                    </label>
                    <input type="date" id="date" name="date"
                        class="w-full border rounded-lg shadow-sm p-3 focus:outline-none focus:ring-1 focus:ring-[#e57373] focus:border-[#e57373]"
                        required>
                </div>

                {{-- Botones --}}
                <div class="flex gap-3">
                    <a href="{{ route('dashboard') }}"
                        class="w-1/2 bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 px-4 rounded-lg shadow-md text-center transition">
                        🔙 Volver
                    </a>
                    <button type="submit"
                        class="w-1/2 bg-[#7a86a1] hover:bg-[#5c677f] text-white font-bold py-3 px-4 rounded-lg shadow-md transition">
                        🚀 Predecir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
