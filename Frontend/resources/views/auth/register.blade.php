@extends('layouts.app')

@section('content')
<div class="w-full max-w-sm bg-white rounded-2xl shadow-xl p-6 mx-4 border border-[#c9c5c4] flex flex-col">
    <h2 class="text-2xl font-bold text-[#525d79] mb-5 text-center">Registro de Hotel</h2>

    <form method="POST" action="{{ route('register') }}" class="flex flex-col flex-grow">
        @csrf

        <!-- Nombre del Hotel -->
        <div class="mb-3">
            <label for="name" class="block text-[#5c677f] font-semibold mb-1">Nombre del Hotel</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required
                class="w-full px-3 py-2 border rounded-lg 
                       focus:outline-none focus:ring-1 focus:ring-[#e57373] focus:border-[#e57373]">
            @error('name')
                <p class="text-[#e57373] text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Dirección -->
        <div class="mb-3">
            <label for="address" class="block text-[#5c677f] font-semibold mb-1">Dirección</label>
            <input id="address" type="text" name="address" value="{{ old('address') }}" required
                class="w-full px-3 py-2 border rounded-lg 
                       focus:outline-none focus:ring-1 focus:ring-[#e57373] focus:border-[#e57373]">
            @error('address')
                <p class="text-[#e57373] text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Número de Habitaciones -->
        <div class="mb-3">
            <label for="rooms_number" class="block text-[#5c677f] font-semibold mb-1">Número de Habitaciones</label>
            <input id="rooms_number" type="number" name="rooms_number" value="{{ old('rooms_number', 1) }}" min="1" required
                class="w-full px-3 py-2 border rounded-lg 
                       focus:outline-none focus:ring-1 focus:ring-[#e57373] focus:border-[#e57373]">
            @error('rooms_number')
                <p class="text-[#e57373] text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="block text-[#5c677f] font-semibold mb-1">Correo Electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                class="w-full px-3 py-2 border rounded-lg 
                       focus:outline-none focus:ring-1 focus:ring-[#e57373] focus:border-[#e57373]">
            @error('email')
                <p class="text-[#e57373] text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Contraseña -->
        <div class="mb-3">
            <label for="password" class="block text-[#5c677f] font-semibold mb-1">Contraseña</label>
            <input id="password" type="password" name="password" required
                class="w-full px-3 py-2 border rounded-lg 
                       focus:outline-none focus:ring-1 focus:ring-[#e57373] focus:border-[#e57373]">
            @error('password')
                <p class="text-[#e57373] text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirmar Contraseña -->
        <div class="mb-4">
            <label for="password_confirmation" class="block text-[#5c677f] font-semibold mb-1">Confirmar Contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="w-full px-3 py-2 border rounded-lg 
                       focus:outline-none focus:ring-1 focus:ring-[#e57373] focus:border-[#e57373]">
        </div>

        <!-- Botón + Enlaces -->
        <div class="flex flex-col items-center mt-2">
            <button type="submit"
                class="w-full bg-[#7a86a1] hover:bg-[#5c677f] text-white font-bold py-2 px-3 rounded-lg transition-colors">
                Registrarse
            </button>

            <p class="mt-3 text-sm text-[#5c677f]">
                ¿Ya tienes una cuenta? 
                <a href="{{ route('login') }}" 
                   class="ml-1 font-semibold text-[#e57373] hover:text-[#ef5350]">
                    Inicia sesión aquí
                </a>
            </p>
        </div>
    </form>
</div>
@endsection
