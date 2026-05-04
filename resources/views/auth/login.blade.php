<x-guest-layout>
    @if ($errors->has('login') || $errors->has('password'))
        <div x-data="{ showErrorModal: true }" x-show="showErrorModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-slate-900/35 backdrop-blur-[1px]"></div>
            <div class="relative w-full max-w-sm rounded-2xl bg-white shadow-xl border border-slate-100 p-4 sm:p-5">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full bg-red-50 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-base font-semibold text-slate-900">No se pudo iniciar sesión</h2>
                        <p class="text-sm text-slate-600 mt-0.5">Verifica tu correo o usuario y tu contraseña.</p>
                    </div>
                    <button type="button" @click="showErrorModal = false" class="text-slate-400 hover:text-slate-700 text-lg leading-none">&times;</button>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" @click="showErrorModal = false" class="px-3.5 py-1.5 rounded-lg bg-slate-900 text-white text-xs font-semibold tracking-wide hover:bg-slate-800">
                        Entendido
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="text-center mb-6">
        <img src="{{ asset('images/logo-solyluna.png') }}" alt="Solyluna" class="w-[130px] h-[130px] mx-auto mb-3" />
        <h1 class="text-[3.25rem] font-extrabold leading-none tracking-wide inline-flex items-center justify-center gap-4">
            <span style="color: #0059b6;">SOL</span>
            <span style="color: #e3ad0e;" class="-mt-2 inline-block">&</span>
            <span style="color: #0059b6;">LUNA</span>
        </h1>
        <p class="text-sm text-gray-600 mt-1">Inicia sesión para gestionar el sistema</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="login" :value="__('Correo o usuario')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Recordarme') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Ingresar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
