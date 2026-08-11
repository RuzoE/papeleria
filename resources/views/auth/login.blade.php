<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full">

    <div
        class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-800 via-slate-900 to-indigo-950">

        {{-- Header --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <div class="mx-auto w-20 h-20 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-2xl mb-6">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white tracking-tight">{{ config('app.name') }}</h1>
            <p class="mt-2 text-slate-400 text-sm">Sistema de Gestión</p>
        </div>

        {{-- Card --}}
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white/5 backdrop-blur-xl py-8 px-6 shadow-2xl rounded-2xl border border-white/10 sm:px-10">

                {{-- Error global --}}
                @if ($errors->any())
                    <div class="mb-5 bg-red-500/10 border border-red-500/20 rounded-xl px-4 py-3 flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-red-400 text-sm">{{ $errors->first() }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">
                            Correo electrónico
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            autocomplete="email"
                            class="block w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="admin@papeleria.local">
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">
                            Contraseña
                        </label>
                        <input type="password" id="password" name="password" required autocomplete="current-password"
                            class="block w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="••••••••">
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="h-4 w-4 rounded border-slate-600 bg-white/5 text-indigo-600 focus:ring-indigo-500">
                        <label for="remember" class="ml-2 text-sm text-slate-400">Recordar sesión</label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 active:scale-95">
                        Ingresar al sistema
                    </button>
                </form>
            </div>

            <p class="mt-6 text-center text-xs text-slate-600">
                {{ config('app.name') }} &copy; {{ date('Y') }} — Uso exclusivo interno
            </p>
        </div>
    </div>

</body>

</html>