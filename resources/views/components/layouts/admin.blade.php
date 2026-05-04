<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Sistema Solyluna') }}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div x-data="{ sidebarOpen: true, userMenuOpen: false }" class="min-h-screen flex">
            <aside
                class="bg-blue-900 text-white transition-all duration-200"
                :class="sidebarOpen ? 'w-64' : 'w-20'"
            >
                <div class="pt-4 px-3 flex" :class="sidebarOpen ? 'justify-end' : 'justify-center'">
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="w-10 h-10 rounded-lg bg-transparent border border-transparent text-white/90 hover:bg-blue-800/70 hover:border-blue-600 flex items-center justify-center transition-colors"
                        :title="sidebarOpen ? 'Cerrar menú' : 'Abrir menú'"
                    >
                        <span x-show="sidebarOpen" class="text-xl leading-none">✕</span>
                        <span x-show="!sidebarOpen" class="text-xl leading-none">☰</span>
                    </button>
                </div>

                <div class="pt-4 pb-5 px-2 flex items-center justify-center border-b border-blue-800 mb-4">
                    <img
                        src="{{ asset('images/logo-solyluna-siderbar.png') }}"
                        alt="Solyluna Sidebar"
                        class="object-contain transition-all duration-200"
                        :class="sidebarOpen ? 'w-[160px] h-[160px]' : 'w-12 h-12'"
                    >
                </div>

                <nav class="p-3 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-amber-400 text-blue-950 font-semibold' : 'hover:bg-blue-800' }}">
                        <span x-show="sidebarOpen">Dashboard</span>
                        <span x-show="!sidebarOpen">D</span>
                    </a>
                    <a href="{{ route('admin.ventas') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.ventas') ? 'bg-amber-400 text-blue-950 font-semibold' : 'hover:bg-blue-800' }}">
                        <span x-show="sidebarOpen">Ventas</span>
                        <span x-show="!sidebarOpen">V</span>
                    </a>
                    <a href="{{ route('admin.productos') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.productos') ? 'bg-amber-400 text-blue-950 font-semibold' : 'hover:bg-blue-800' }}">
                        <span x-show="sidebarOpen">Productos</span>
                        <span x-show="!sidebarOpen">P</span>
                    </a>
                    <a href="{{ route('admin.habitaciones') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.habitaciones') ? 'bg-amber-400 text-blue-950 font-semibold' : 'hover:bg-blue-800' }}">
                        <span x-show="sidebarOpen">Habitaciones</span>
                        <span x-show="!sidebarOpen">H</span>
                    </a>
                    <a href="{{ route('admin.clientes') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.clientes') ? 'bg-amber-400 text-blue-950 font-semibold' : 'hover:bg-blue-800' }}">
                        <span x-show="sidebarOpen">Clientes</span>
                        <span x-show="!sidebarOpen">C</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.users.*') ? 'bg-amber-400 text-blue-950 font-semibold' : 'hover:bg-blue-800' }}">
                        <span x-show="sidebarOpen">Usuarios</span>
                        <span x-show="!sidebarOpen">U</span>
                    </a>
                    <div class="pt-3 mt-3 border-t border-blue-800/70">
                        <a href="{{ route('admin.configuracion') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.configuracion') ? 'bg-amber-400 text-blue-950 font-semibold' : 'hover:bg-blue-800' }}">
                            <span x-show="sidebarOpen">Configuración</span>
                            <span x-show="!sidebarOpen">⚙</span>
                        </a>
                    </div>
                </nav>
            </aside>

            <div class="flex-1 flex flex-col">
                <header class="h-16 bg-white border-b px-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <h1 class="text-lg font-semibold text-gray-800">{{ $title ?? 'Panel de Administracion' }}</h1>
                    </div>
                    <div class="relative">
                        <button
                            @click="userMenuOpen = !userMenuOpen"
                            class="px-4 py-2 rounded-xl border border-gray-300 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50"
                        >
                            {{ strtoupper(auth()->user()->name) }}
                        </button>

                        <div
                            x-show="userMenuOpen"
                            @click.outside="userMenuOpen = false"
                            x-transition
                            class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-2xl shadow-xl p-2 z-20"
                            style="display: none;"
                        >
                            <a href="#" class="block px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-100">Mi perfil</a>
                            <a href="#" class="block px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-100">Configuración</a>
                            <a href="#" class="block px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-100">Ayuda</a>
                            <a href="#" class="block px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-100">Soporte</a>
                            <hr class="my-2 border-gray-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm text-red-600 hover:bg-red-50">
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <main class="p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
