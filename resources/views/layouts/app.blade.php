<!DOCTYPE html>
<html lang="es" class="h-full" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="h-full bg-slate-50 font-sans antialiased" x-cloak>

    <div class="flex h-screen overflow-hidden">

        {{-- ════════════════════════════════════════════════════════
        SIDEBAR
        ════════════════════════════════════════════════════════ --}}
        {{-- Mobile overlay --}}
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-20 lg:hidden" x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false">
        </div>

        {{-- Sidebar panel --}}
        <aside class="fixed inset-y-0 left-0 z-30 flex flex-col bg-slate-900 transition-all duration-300 ease-in-out
                  lg:static lg:translate-x-0
                  lg:w-64" :class="{
               '-translate-x-full': !sidebarOpen,
               'translate-x-0': sidebarOpen,
               'w-64': !sidebarCollapsed,
               'lg:w-16': sidebarCollapsed
           }">

            {{-- Logo --}}
            <div class="flex items-center gap-3 h-16 px-4 border-b border-slate-700/50 flex-shrink-0">
                <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <span class="text-white font-bold text-sm tracking-wide truncate" x-show="!sidebarCollapsed">
                    {{ config('app.name') }}
                </span>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-1 scrollbar-thin">

                @php
                    $navItems = [
                        ['route' => 'dashboard', 'icon' => 'home', 'label' => 'Dashboard'],
                    ];
                @endphp

                {{-- Dashboard --}}
                <x-sidebar-link route="dashboard" icon="home" label="Dashboard" />

                {{-- Inventario --}}
                <x-sidebar-group label="Inventario" icon="cube">
                    <x-sidebar-link route="products.index" icon="tag" label="Productos" indent />
                    <x-sidebar-link route="categories.index" icon="folder" label="Categorías" indent />
                    <x-sidebar-link route="locations.index" icon="map-pin" label="Ubicaciones" indent />
                    <x-sidebar-link route="suppliers.index" icon="truck" label="Proveedores" indent />
                    <x-sidebar-link route="inventory.index" icon="arrows" label="Movimientos" indent />
                    <x-sidebar-link route="purchases.index" icon="shopping-bag" label="Compras" indent />
                </x-sidebar-group>

                {{-- Ventas --}}
                <x-sidebar-group label="Ventas" icon="cash">
                    <x-sidebar-link route="sales.create" icon="plus-circle" label="Nueva Venta" indent />
                    <x-sidebar-link route="sales.index" icon="list" label="Historial" indent />
                </x-sidebar-group>

                {{-- Servicios --}}
                <x-sidebar-group label="Servicios" icon="currency-dollar">
                    <x-sidebar-link route="transactions.create" icon="plus-circle" label="Nueva Transacción" indent />
                    <x-sidebar-link route="transactions.index" icon="list" label="Historial" indent />
                    <x-sidebar-link route="transaction-types.index" icon="cog" label="Tipos" indent />
                    <x-sidebar-link route="transaction-rates.index" icon="adjustments" label="Tarifas" indent />
                </x-sidebar-group>

                {{-- Caja --}}
                <x-sidebar-group label="Caja" icon="calculator">
                    <x-sidebar-link route="cash.open" icon="lock-open" label="Apertura" indent />
                    <x-sidebar-link route="cash.movements" icon="list" label="Movimientos" indent />
                    <x-sidebar-link route="cash.close" icon="lock" label="Cierre" indent />
                </x-sidebar-group>

                {{-- Reportes --}}
                <x-sidebar-link route="reports.index" icon="chart-bar" label="Reportes" />

                {{-- Admin group --}}
                @if(auth()->user()->isAdmin())
                    <x-sidebar-group label="Administración" icon="shield">
                        <x-sidebar-link route="users.index" icon="users" label="Usuarios" indent />
                        <x-sidebar-link route="audit.index" icon="eye" label="Auditoría" indent />
                        <x-sidebar-link route="settings.index" icon="cog" label="Configuración" indent />
                    </x-sidebar-group>
                @endif

            </nav>

            {{-- Bottom: collapse + user mini --}}
            <div class="border-t border-slate-700/50 p-3">
                <button @click="sidebarCollapsed = !sidebarCollapsed"
                    class="hidden lg:flex w-full items-center gap-2 text-slate-400 hover:text-white text-xs rounded-lg px-2 py-2 hover:bg-slate-800 transition">
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform" :class="{'rotate-180': sidebarCollapsed}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                    <span x-show="!sidebarCollapsed">Contraer menú</span>
                </button>
            </div>
        </aside>

        {{-- ════════════════════════════════════════════════════════
        MAIN AREA
        ════════════════════════════════════════════════════════ --}}
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

            {{-- Navbar --}}
            <header
                class="h-16 bg-white border-b border-slate-200 flex items-center px-4 gap-4 flex-shrink-0 z-10 shadow-sm">

                {{-- Mobile menu button --}}
                <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                {{-- Page title --}}
                <h2 class="text-slate-700 font-semibold text-sm flex-1 truncate">
                    @yield('page-title', 'Dashboard')
                </h2>

                {{-- Right side --}}
                <div class="flex items-center gap-3">

                    {{-- Date/time --}}
                    <span class="hidden sm:block text-xs text-slate-400">
                        {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM YYYY') }}
                    </span>

                    {{-- User dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 transition">
                            <div
                                class="w-7 h-7 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span
                                class="hidden sm:block font-medium truncate max-w-32">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition @click.outside="open = false"
                            class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs font-semibold text-slate-700 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                <span
                                    class="inline-block mt-1 text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-medium">
                                    {{ auth()->user()->role?->label() ?? auth()->user()->role }}
                                </span>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Flash messages --}}
            @if(session('success') || session('error') || session('warning') || session('info'))
                <div class="px-4 sm:px-6 pt-4" x-data="{ show: true }" x-show="show">
                    @if(session('success'))
                        <x-alert type="success" :message="session('success')" dismissible />
                    @endif
                    @if(session('error'))
                        <x-alert type="error" :message="session('error')" dismissible />
                    @endif
                    @if(session('warning'))
                        <x-alert type="warning" :message="session('warning')" dismissible />
                    @endif
                    @if(session('info'))
                        <x-alert type="info" :message="session('info')" dismissible />
                    @endif
                </div>
            @endif

            {{-- Page content --}}
            <main class="flex-1 overflow-y-auto">
                <div class="p-4 sm:p-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>