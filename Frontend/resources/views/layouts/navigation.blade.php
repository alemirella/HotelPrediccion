<!-- Navegación -->
<div class="hidden sm:flex sm:items-center sm:ms-6">
    @auth
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <div>{{ Auth::user()->name }}</div>
                    <div class="ms-2">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
            </x-slot>
            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100 rounded-lg">
                    Perfil
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100 rounded-lg"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Cerrar sesión
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    @else
        <a href="{{ route('login') }}"
            class="text-sm text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-lg font-medium me-2">
            Login
        </a>
        <a href="{{ route('register') }}"
            class="text-sm text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded-lg font-medium">
            Register
        </a>
    @endauth
</div>
