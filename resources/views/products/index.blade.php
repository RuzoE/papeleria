@extends('layouts.app')

@section('title', 'Productos')
@section('page-title', 'Catálogo de Productos')

@section('content')

    {{-- Top Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Inventario de Productos</h1>
            <p class="text-xs text-slate-500 mt-0.5">Gestión de existencias, precios, códigos de barras y ubicaciones</p>
        </div>
        <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-xl shadow-sm transition">
            <x-icon name="plus" class="w-4 h-4" />
            Nuevo Producto
        </a>
    </div>

    {{-- Filters & Search --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6">
        <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            <div class="relative lg:col-span-2">
                <x-icon name="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" />
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por código, nombre o marca..." class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition" />
            </div>

            <div>
                <select name="category_id" onchange="this.form.submit()" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @foreach($cat->children as $child)
                            <option value="{{ $child->id }}" {{ request('category_id') == $child->id ? 'selected' : '' }}>↳ {{ $child->name }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <div>
                <select name="stock_status" onchange="this.form.submit()" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <option value="">Estado Stock</option>
                    <option value="normal" {{ request('stock_status') === 'normal' ? 'selected' : '' }}>Stock Normal</option>
                    <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Stock Bajo</option>
                    <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Agotados</option>
                </select>
            </div>

            <div>
                <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <option value="">Estado Producto</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
                    Filtrar
                </button>
                @if(request()->hasAny(['search', 'category_id', 'stock_status', 'status', 'supplier_id', 'location_id']))
                    <a href="{{ route('products.index') }}" class="px-3 py-2 text-sm text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Products Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-100 text-xs text-slate-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3.5">Producto</th>
                        <th class="px-5 py-3.5">Códigos</th>
                        <th class="px-5 py-3.5">Categoría / Ubicación</th>
                        <th class="px-5 py-3.5 text-right">Precio Venta</th>
                        <th class="px-5 py-3.5 text-center">Stock</th>
                        <th class="px-5 py-3.5 text-center">Estado</th>
                        <th class="px-5 py-3.5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-3.5 font-medium text-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('products.show', $product) }}" class="font-bold text-slate-800 hover:text-indigo-600 transition truncate block">
                                            {{ $product->name }}
                                        </a>
                                        <span class="text-xs text-slate-400 font-normal">
                                            {{ $product->brand ?? 'Sin marca' }} • {{ $product->unit }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs font-mono bg-slate-100 text-slate-700 px-2 py-0.5 rounded w-max font-semibold">
                                        {{ $product->internal_code }}
                                    </span>
                                    @if($product->barcode)
                                        <span class="text-xs font-mono text-slate-500 flex items-center gap-1">
                                            <x-icon name="barcode" class="w-3 h-3 text-slate-400" />
                                            {{ $product->barcode }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-xs font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full w-max">
                                        {{ $product->category->name }}
                                    </span>
                                    @if($product->location)
                                        <span class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                            <x-icon name="map-pin" class="w-3 h-3 text-slate-400" />
                                            {{ $product->location->full_code }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-5 py-3.5 text-right font-bold text-slate-800">
                                ${{ number_format($product->sale_price, 0, ',', '.') }}
                                <span class="block text-xs font-normal text-slate-400">
                                    Costo: ${{ number_format($product->purchase_price, 0, ',', '.') }}
                                </span>
                            </td>

                            <td class="px-5 py-3.5 text-center">
                                @if($product->is_out_of_stock)
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-red-600 bg-red-50 px-2.5 py-1 rounded-full border border-red-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Agotado (0)
                                    </span>
                                @elseif($product->is_low_stock)
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        {{ $product->stock }} (Bajo)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                                        {{ $product->stock }} {{ $product->unit }}s
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-3.5 text-center">
                                <form method="POST" action="{{ route('products.toggle-status', $product) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold transition {{ $product->status->value === 'active' ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $product->status->value === 'active' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        {{ $product->status->label() }}
                                    </button>
                                </form>
                            </td>

                            <td class="px-5 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('products.show', $product) }}" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Ver detalles">
                                        <x-icon name="eye" class="w-4 h-4" />
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Editar">
                                        <x-icon name="pencil" class="w-4 h-4" />
                                    </a>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Eliminar">
                                            <x-icon name="trash" class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <x-icon name="tag" class="w-10 h-10 text-slate-300 mx-auto mb-2" />
                                <p class="text-sm text-slate-500 font-medium">No se encontraron productos</p>
                                <p class="text-xs text-slate-400 mt-1">Ajusta los filtros o agrega tu primer producto al catálogo.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>

@endsection
