@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    {{-- ─── KPI Grid ─────────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

        <x-stat-card title="Productos Registrados" :value="number_format($totalProducts)" icon="tag" color="indigo" />

        <x-stat-card title="Ventas Hoy" :value="'$' . number_format($salesToday, 0, ',', '.')" icon="cash" color="emerald"
            :subtitle="$salesCountToday . ' ventas'" />

        <x-stat-card title="Ventas del Mes" :value="'$' . number_format($salesMonth, 0, ',', '.')" icon="trending-up"
            color="blue" />

        <x-stat-card title="Ingresos Servicios" :value="'$' . number_format($servicesIncomeToday, 0, ',', '.')"
            icon="currency-dollar" color="purple" :subtitle="$transactionsToday . ' transacciones hoy'" />

    </div>

    {{-- ─── Stock Alerts Row ─────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

        <x-stat-card title="Productos Activos" :value="number_format($activeProducts)" icon="check-circle"
            color="emerald" />

        <x-stat-card title="Stock Bajo" :value="number_format($lowStockProducts)" icon="exclamation" color="amber"
            subtitle="Stock ≤ mínimo" />

        <x-stat-card title="Agotados" :value="number_format($outOfStockProducts)" icon="x-circle" color="red"
            subtitle="Stock = 0" />

        <x-stat-card title="Servicios Hoy" :value="number_format($transactionsToday)" icon="adjustments" color="indigo"
            subtitle="Transacciones del día" />

    </div>

    {{-- ─── Alerts + Top Products ────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        {{-- Low stock alerts --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="flex items-center gap-2 px-5 py-4 border-b border-slate-100">
                <x-icon name="exclamation" class="w-4 h-4 text-amber-500" />
                <h3 class="text-sm font-semibold text-slate-700">Productos con Stock Bajo</h3>
                @if($lowStockProducts > 0)
                    <span class="ml-auto bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                        {{ $lowStockProducts }}
                    </span>
                @endif
            </div>

            @if($lowStockList->count() > 0)
                <ul class="divide-y divide-slate-50">
                    @foreach($lowStockList as $product)
                        <li class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50 transition">
                            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <x-icon name="tag" class="w-4 h-4 text-amber-600" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-700 truncate">{{ $product->name }}</p>
                                <p class="text-xs text-slate-400">{{ $product->category?->name ?? '—' }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="text-sm font-bold text-amber-600">{{ $product->stock }}</span>
                                <span class="text-xs text-slate-400 ml-1">/ {{ $product->minimum_stock }} mín</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="px-5 py-8 text-center">
                    <x-icon name="check-circle" class="w-8 h-8 text-emerald-400 mx-auto mb-2" />
                    <p class="text-sm text-slate-500">Todos los productos tienen stock suficiente</p>
                </div>
            @endif
        </div>

        {{-- Out of stock --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="flex items-center gap-2 px-5 py-4 border-b border-slate-100">
                <x-icon name="x-circle" class="w-4 h-4 text-red-500" />
                <h3 class="text-sm font-semibold text-slate-700">Productos Agotados</h3>
                @if($outOfStockProducts > 0)
                    <span class="ml-auto bg-red-100 text-red-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                        {{ $outOfStockProducts }}
                    </span>
                @endif
            </div>

            @if($outOfStockList->count() > 0)
                <ul class="divide-y divide-slate-50">
                    @foreach($outOfStockList as $product)
                        <li class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50 transition">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <x-icon name="tag" class="w-4 h-4 text-red-600" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-700 truncate">{{ $product->name }}</p>
                                <p class="text-xs text-slate-400">{{ $product->category?->name ?? '—' }}</p>
                            </div>
                            <x-badge value="agotado" />
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="px-5 py-8 text-center">
                    <x-icon name="check-circle" class="w-8 h-8 text-emerald-400 mx-auto mb-2" />
                    <p class="text-sm text-slate-500">No hay productos agotados</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ─── Top Products ─────────────────────────────────────────────────────── --}}
    @if($topProducts->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="flex items-center gap-2 px-5 py-4 border-b border-slate-100">
                <x-icon name="trending-up" class="w-4 h-4 text-emerald-500" />
                <h3 class="text-sm font-semibold text-slate-700">Productos Más Vendidos</h3>
                <span class="ml-auto text-xs text-slate-400">Últimos 30 días</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">#</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                Producto</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                Unidades vendidas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($topProducts as $i => $product)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-3 text-slate-400 font-medium">{{ $i + 1 }}</td>
                                <td class="px-5 py-3 text-slate-700 font-medium">{{ $product->name }}</td>
                                <td class="px-5 py-3 text-right">
                                    <span class="text-emerald-600 font-bold">{{ number_format($product->total_sold) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection