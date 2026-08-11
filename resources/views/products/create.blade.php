@extends('layouts.app')

@section('title', 'Nuevo Producto')
@section('page-title', 'Crear Producto')

@section('content')

    <div x-data="{
            purchasePrice: {{ old('purchase_price', 0) }},
            salePrice: {{ old('sale_price', 0) }},
            imagePreview: null,

            get profitMargin() {
                let cost = parseFloat(this.purchasePrice) || 0;
                let price = parseFloat(this.salePrice) || 0;
                if (cost <= 0) return 0;
                return (((price - cost) / cost) * 100).toFixed(1);
            },

            get profitAmount() {
                let cost = parseFloat(this.purchasePrice) || 0;
                let price = parseFloat(this.salePrice) || 0;
                return (price - cost).toFixed(0);
            },

            fileChosen(event) {
                let file = event.target.files[0];
                if (!file) return;
                let reader = new FileReader();
                reader.onload = (e) => { this.imagePreview = e.target.result; };
                reader.readAsDataURL(file);
            }
        }">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Agregar Nuevo Producto</h1>
                <p class="text-xs text-slate-500 mt-0.5">Ingresa los datos para incorporar el producto al inventario</p>
            </div>
            <a href="{{ route('products.index') }}"
                class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 bg-white border border-slate-200 text-sm font-medium px-4 py-2.5 rounded-xl shadow-sm transition">
                <x-icon name="arrow-left" class="w-4 h-4" />
                Volver al catálogo
            </a>
        </div>

        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data"
            class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf

            {{-- Main Column (Left/Center - 2 Cols) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Basic Details Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
                    <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3">Información General</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Código
                                de Barras (Escáner)</label>
                            <div class="relative">
                                <x-icon name="barcode"
                                    class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" />
                                <input type="text" name="barcode" value="{{ old('barcode') }}" autofocus
                                    class="w-full pl-10 pr-4 py-2.5 text-sm font-mono border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition"
                                    placeholder="Escanear o ingresar EAN/UPC..." />
                            </div>
                            <span class="text-xs text-slate-400 mt-1 block">Opcional. Si se omite, se asignará un código
                                interno.</span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Nombre
                                del Producto <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition"
                                placeholder="Ej. Cuaderno Cosido 100 Hojas Rayado" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Marca /
                                Fabricante</label>
                            <input type="text" name="brand" value="{{ old('brand') }}"
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition"
                                placeholder="Ej. Norma, Faber-Castell, Pelikan" />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Unidad
                                de Medida <span class="text-red-500">*</span></label>
                            <select name="unit"
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                                <option value="unidad" {{ old('unit') === 'unidad' ? 'selected' : '' }}>Unidad</option>
                                <option value="caja" {{ old('unit') === 'caja' ? 'selected' : '' }}>Caja</option>
                                <option value="paquete" {{ old('unit') === 'paquete' ? 'selected' : '' }}>Paquete</option>
                                <option value="resma" {{ old('unit') === 'resma' ? 'selected' : '' }}>Resma</option>
                                <option value="metro" {{ old('unit') === 'metro' ? 'selected' : '' }}>Metro</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Descripción</label>
                        <textarea name="description" rows="3"
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition"
                            placeholder="Detalles de presentación, gramaje, color o especificidades..."></textarea>
                    </div>
                </div>

                {{-- Prices & Inventory Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
                    <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3">Precios y Control de
                        Existencias</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Precio
                                de Compra (Costo) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold">$</span>
                                <input type="number" step="0.01" min="0" name="purchase_price" x-model="purchasePrice"
                                    required
                                    class="w-full pl-8 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Precio
                                de Venta al Público <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold">$</span>
                                <input type="number" step="0.01" min="0" name="sale_price" x-model="salePrice" required
                                    class="w-full pl-8 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition" />
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Profit Metric Bar --}}
                    <div class="bg-indigo-50/60 rounded-xl p-3.5 flex items-center justify-between text-xs">
                        <span class="text-indigo-800 font-semibold">Margen de Ganancia estimado:</span>
                        <div class="flex items-center gap-3">
                            <span class="text-slate-600">Ganancia: <strong>$<span
                                        x-text="profitAmount"></span></strong></span>
                            <span class="bg-indigo-600 text-white font-bold px-2.5 py-1 rounded-lg"
                                x-text="profitMargin + '%'"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Stock
                                Inicial <span class="text-red-500">*</span></label>
                            <input type="number" min="0" name="stock" value="{{ old('stock', 0) }}" required
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition" />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Stock
                                Mínimo (Alerta) <span class="text-red-500">*</span></label>
                            <input type="number" min="0" name="minimum_stock" value="{{ old('minimum_stock', 5) }}" required
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition" />
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Sidebar Column (1 Col) --}}
            <div class="space-y-6">

                {{-- Categorization Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
                    <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3">Clasificación y Ubicación
                    </h2>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Categoría
                            <span class="text-red-500">*</span></label>
                        <select name="category_id" required
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                            <option value="">Seleccionar Categoría</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Ubicación
                            en Almacén</label>
                        <select name="location_id"
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                            <option value="">Sin ubicación específica</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->full_code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Proveedor
                            Habitual</label>
                        <select name="supplier_id"
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                            <option value="">Sin proveedor preferido</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                                    {{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Estado
                            Inicial</label>
                        <select name="status"
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Activo
                            </option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>

                {{-- Product Image Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
                    <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3">Imagen del Producto</h2>

                    <div class="text-center">
                        <div
                            class="w-full h-40 rounded-xl bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center overflow-hidden mb-3 relative group">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" class="w-full h-full object-cover" />
                            </template>
                            <template x-if="!imagePreview">
                                <div class="flex flex-col items-center">
                                    <x-icon name="photograph" class="w-8 h-8 text-slate-300 mb-1" />
                                    <span class="text-xs text-slate-400">Formatos JPG, PNG, WEBP (Máx 2MB)</span>
                                </div>
                            </template>
                        </div>

                        <label
                            class="cursor-pointer inline-flex items-center gap-2 text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-xl transition">
                            <x-icon name="upload" class="w-4 h-4" />
                            Seleccionar Imagen
                            <input type="file" name="image" accept="image/*" @change="fileChosen" class="hidden" />
                        </label>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('products.index') }}"
                        class="w-1/2 text-center px-4 py-3 text-sm font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl shadow-sm transition">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="w-1/2 text-center px-4 py-3 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                        Guardar Producto
                    </button>
                </div>

            </div>
        </form>

    </div>

@endsection