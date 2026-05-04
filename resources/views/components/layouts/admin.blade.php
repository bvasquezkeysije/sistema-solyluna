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
        <div x-data="{ sidebarOpen: true }" class="min-h-screen flex">
            <aside
                class="bg-blue-900 text-white transition-all duration-200"
                :class="sidebarOpen ? 'w-64' : 'w-20'"
            >
                <div class="h-16 px-4 flex items-center border-b border-blue-800">
                    <img src="{{ asset('images/logo-solyluna.png') }}" alt="Solyluna" class="w-10 h-10 rounded-full bg-white p-1">
                    <span x-show="sidebarOpen" class="ml-3 font-semibold">Admin Solyluna</span>
                </div>

                <nav class="p-3 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-amber-400 text-blue-950 font-semibold' : 'hover:bg-blue-800' }}">
                        <span x-show="sidebarOpen">Dashboard</span>
                        <span x-show="!sidebarOpen">D</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.users.*') ? 'bg-amber-400 text-blue-950 font-semibold' : 'hover:bg-blue-800' }}">
                        <span x-show="sidebarOpen">Usuarios</span>
                        <span x-show="!sidebarOpen">U</span>
                    </a>
                </nav>
            </aside>

            <div class="flex-1 flex flex-col">
                <header class="h-16 bg-white border-b px-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button
                            @click="sidebarOpen = !sidebarOpen"
                            class="px-3 py-1.5 rounded border border-gray-300 text-sm hover:bg-gray-100"
                        >
                            Menu
                        </button>
                        <h1 class="text-lg font-semibold text-gray-800">{{ $title ?? 'Panel de Administracion' }}</h1>
                    </div>
                    <div class="flex items-center gap-4 text-sm">
                        <span class="text-gray-600">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-red-600 hover:underline">Salir</button>
                        </form>
                    </div>
                </header>

                <main class="p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
