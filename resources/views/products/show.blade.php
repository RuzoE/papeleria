@extends('layouts.app')

@section('title', $product->name)
@section('page-title', 'Detalle de Producto')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('products.index') }}"
                class="p-2 text-slate-400 hover:text-slate-600 bg-white border border-slate-200 rounded-xl transition">
                <x-icon name="arrow-left" class="w-4 h-4" />
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-800">{{ $product->name }}</h1>
                <span class="text-xs text-slate-400 font-mono">Código: {{ $product->internal_code }}</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('products.edit', $product) }}"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition">
                <x-icon name="pencil" class="w-4 h-4" />
                Editar Producto
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-6">
            <div
                class="w-full h-56 rounded-2xl bg-slate-50 border border-slate-100 overflow-hidden flex items-center justify-center">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Estado</span>
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $product->status->value === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        <span
                            class="w-1.5 h-1.5 rounded-full {{ $product->status->value === 'active' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                        {{ $product->status->label() }}
                    </span>
                </div>

                <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Código de Barras</span>
                    <span class="text-sm font-mono font-bold text-slate-700">{{ $product->barcode ?? 'Sin código' }}</span>
                </div>

                <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Marca</span>
                    <span class="text-sm font-medium text-slate-700">{{ $product->brand ?? '—' }}</span>
                </div>

                <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Unidad</span>
                    <span class="text-sm font-medium text-slate-700 capitalize">{{ $product->unit }}</span>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide block mb-1">Precio
                        Venta</span>
                    <span
                        class="text-2xl font-black text-slate-800">${{ number_format($product->sale_price, 0, ',', '.') }}</span>
                    <span class="text-xs text-slate-400 block mt-1">Costo:
                        ${{ number_format($product->purchase_price, 0, ',', '.') }}</span>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide block mb-1">Margen de
                        Ganancia</span>
                    <span class="text-2xl font-black text-indigo-600">{{ $product->profit_margin }}%</span>
                    <span class="text-xs text-slate-400 block mt-1">
                        Utilidad: ${{ number_format($product->sale_price - $product->purchase_price, 0, ',', '.') }}
                    </span>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide block mb-1">Existencias en
                        Stock</span>
                    <span
                        class="text-2xl font-black {{ $product->is_out_of_stock ? 'text-red-600' : ($product->is_low_stock ? 'text-amber-600' : 'text-emerald-600') }}">
                        {{ $product->stock }} {{ $product->unit }}s
                    </span>
                    <span class="text-xs text-slate-400 block mt-1">Mínimo requerido: {{ $product->minimum_stock }}</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3">Detalles de Almacenamiento y
                    Proveedor</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase block mb-1">Categoría</span>
                        <span class="font-bold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg inline-block">
                            {{ $product->category->name }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase block mb-1">Ubicación Física</span>
                        <span class="font-medium text-slate-700">
                            {{ $product->location ? $product->location->full_code : 'Sin ubicación asignada' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase block mb-1">Proveedor Preferido</span>
                        <span class="font-medium text-slate-700">
                            {{ $product->supplier ? $product->supplier->name : 'Sin proveedor' }}
                        </span>
                    </div>
                </div>

                @if($product->description)
                    <div class="border-t border-slate-100 pt-4">
                        <span class="text-xs font-semibold text-slate-400 uppercase block mb-1">Descripción</span>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $product->description }}</p>
                    </div>
                @endif
            </div>

        </div>

    </div>

@endsection