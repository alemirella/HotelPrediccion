@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-8 py-12">
    <div class="bg-white shadow-2xl rounded-2xl border border-[#c9c5c4] overflow-hidden">

        {{-- Encabezado --}}
        <div class="bg-gradient-to-r from-[#7a86a1] to-[#5c677f] text-white px-10 py-6">
            <h2 class="text-3xl font-bold flex items-center gap-3">
                Predecir Afluencia Turística
            </h2>
            <p class="text-sm text-[#e0e0e0] mt-1">
                Configura el rango de días y la fecha de inicio para generar la predicción.
            </p>
        </div>

        {{-- Contenido principal --}}
        <div class="p-10 bg-[#f9f8f8]">
            <form action="{{ route('predictions.store') }}" method="POST" class="space-y-8">
                @csrf

                {{-- Sección en dos columnas --}}
                <div class="grid grid-cols-2 gap-10">
                    
                    {{-- Rango de días --}}
                    <div>
                        <label for="days" class="block text-[#5c677f] font-semibold mb-3 text-lg">
                            📌 Selecciona rango de días
                        </label>
                        <select id="days" name="days" 
                            class="w-full border border-[#c9c5c4] rounded-xl shadow-sm p-3 text-lg
                                   focus:outline-none focus:ring-2 focus:ring-[#7a86a1] focus:border-[#7a86a1]"
                            required>
                            <option value="">-- Selecciona --</option>
                            <option value="7">7 días</option>
                            <option value="15">15 días</option>
                            <option value="30">30 días</option>
                        </select>
                        <p class="text-sm text-gray-500 mt-2">
                            Define cuántos días abarcará la predicción.
                        </p>
                    </div>

                    {{-- Fecha de inicio --}}
                    <div>
                        <label for="date" class="block text-[#5c677f] font-semibold mb-3 text-lg">
                            📅 Fecha de inicio
                        </label>
                        <input type="date" id="date" name="date"
                            class="w-full border border-[#c9c5c4] rounded-xl shadow-sm p-3 text-lg
                                   focus:outline-none focus:ring-2 focus:ring-[#7a86a1] focus:border-[#7a86a1]"
                            required>
                        <p class="text-sm text-gray-500 mt-2">
                            Selecciona la fecha a partir de la cual comenzará la proyección.
                        </p>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end gap-6 pt-6 border-t border-[#e0e0e0]">
                    <a href="{{ route('dashboard') }}" 
                       class="bg-gray-400 hover:bg-gray-500 text-white font-semibold py-3 px-6 rounded-xl shadow-md transition">
                        🔙 Volver
                    </a>
                    <button type="submit"
                        class="bg-[#7a86a1] hover:bg-[#5c677f] text-white font-semibold py-3 px-6 rounded-xl shadow-md transition">
                        Predecir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
