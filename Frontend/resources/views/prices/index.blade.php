@extends('layouts.app')

@section('content')
<div class="min-h-screen p-8" style="background: linear-gradient(135deg, #f5f2f1 25%, #e0e0f0 100%);">

    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-[#7a86a1] mb-4 md:mb-0">Recomendaciones automáticas de precios</h1>
        <a href="{{ route('dashboard') }}" class="bg-[#7a86a1] text-white px-6 py-2 rounded-xl shadow hover:bg-[#6d7595] transition">
            ← Volver al Dashboard
        </a>
    </div>

    <!-- Formulario para pedir precio recomendado -->
    <div class="w-full max-w-7xl bg-white rounded-2xl shadow-lg p-8 border-t-4 border-[#e57373] mb-8">
        <h2 class="text-2xl font-bold text-[#7a86a1] mb-6">Solicitar Precio Recomendado</h2>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ implode(', ', $errors->all()) }}
            </div>
        @endif

        <form action="{{ route('prices.recommended') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @csrf

            <!-- Primera columna -->
            <div class="space-y-4">
                <input type="number" name="precio_actual" placeholder="Precio Actual" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7a86a1] focus:outline-none">
                <input type="number" name="ocupacion_hotel" placeholder="Ocupación Hotel (%)" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7a86a1] focus:outline-none">
                <input type="number" name="ocupacion_zona" placeholder="Ocupación Zona (%)" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7a86a1] focus:outline-none">
                <input type="number" name="anticipacion_reserva" placeholder="Anticipación Reserva (días)" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7a86a1] focus:outline-none">
            </div>

            <!-- Segunda columna -->
            <div class="space-y-4">
                <select name="dia_semana" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7a86a1] focus:outline-none">
                    <option value="1">Lunes</option>
                    <option value="2">Martes</option>
                    <option value="3">Miércoles</option>
                    <option value="4">Jueves</option>
                    <option value="5">Viernes</option>
                    <option value="6">Sábado</option>
                    <option value="7">Domingo</option>
                </select>

                <select name="mes" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7a86a1] focus:outline-none">
                    <option value="1">Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>

                <select name="tipo_habitacion" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7a86a1] focus:outline-none">
                    <option value="1">Individual</option>
                    <option value="2">Doble</option>
                    <option value="3">Triple</option>
                </select>

                <input type="number" step="0.01" name="competencia_precio_promedio" placeholder="Competencia Precio Promedio" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7a86a1] focus:outline-none">
            </div>

            <!-- Tercera columna -->
            <div class="space-y-4">
                <select name="evento_ciudad" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7a86a1] focus:outline-none">
                    <option value="0">No evento</option>
                    <option value="1">Evento ciudad</option>
                </select>

                <select name="clima" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7a86a1] focus:outline-none">
                    <option value="1">Soleado</option>
                    <option value="2">Lluvioso</option>
                    <option value="3">Nublado</option>
                </select>

                <input type="number" name="demanda_historica" placeholder="Demanda Histórica" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7a86a1] focus:outline-none">

                <select name="feriado" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7a86a1] focus:outline-none">
                    <option value="0">No feriado</option>
                    <option value="1">Feriado</option>
                </select>
            </div>

            <!-- Botón centrado debajo de las columnas -->
            <div class="md:col-span-3 text-center mt-6">
                <button type="submit" class="bg-[#e57373] text-white px-8 py-3 rounded-xl shadow hover:bg-red-600 transition">
                    Obtener Precio Recomendado
                </button>
            </div>
        </form>
    </div>

    <!-- Mostrar precio recomendado -->
    @isset($precio_recomendado)
    <div class="w-full max-w-7xl bg-white rounded-2xl shadow-lg p-8 border-t-4 border-[#7a86a1] text-center mb-8">
        <h2 class="text-2xl font-bold text-[#7a86a1] mb-4">💰 Precio Recomendado</h2>
        <span class="text-5xl font-extrabold text-[#e57373]">S/ {{ $precio_recomendado }}</span>
        <div class="mt-6">
            <a href="{{ route('dashboard') }}" class="inline-block bg-[#7a86a1] text-white px-8 py-3 rounded-xl shadow hover:bg-[#6d7595] transition">
                Volver al Dashboard
            </a>
        </div>
    </div>
    @endisset

</div>
@endsection
