@extends('layouts.app')

@section('title', 'Categorías')
@section('page-title', 'Gestión de Categorías')

@section('content')

    <div x-data="{
            showModal: false,
            isEditing: false,
            formUrl: '{{ route('categories.store') }}',
            formData: { id: null, name: '', description: '', parent_id: '', status: 1 },

            openCreateModal() {
                this.isEditing = false;
                this.formUrl = '{{ route('categories.store') }}';
                this.formData = { id: null, name: '', description: '', parent_id: '', status: 1 };
                this.showModal = true;
            },

            openEditModal(category) {
                this.isEditing = true;
                this.formUrl = '/categories/' + category.id;
                this.formData = {
                    id: category.id,
                    name: category.name,
                    description: category.description || '',
                    parent_id: category.parent_id || '',
                    status: category.status ? 1 : 0
                };
                this.showModal = true;
            }
        }">

        {{-- Top Bar & Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Categorías de Productos</h1>
                <p class="text-xs text-slate-500 mt-0.5">Organiza tu catálogo por secciones y subcategorías</p>
            </div>
            <button @click="openCreateModal()"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-xl shadow-sm transition">
                <x-icon name="plus" class="w-4 h-4" />
                Nueva Categoría
            </button>
        </div>

        {{-- Filters & Search --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6">
            <form method="GET" action="{{ route('categories.index') }}"
                class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <x-icon name="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" />
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Buscar por nombre o descripción..."
                        class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition" />
                </div>

                <select name="status" onchange="this.form.submit()"
                    class="w-full sm:w-44 px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <option value="">Todos los estados</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Activas</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivas</option>
                </select>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="submit"
                        class="flex-1 sm:flex-initial bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
                        Filtrar
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('categories.index') }}"
                            class="px-3 py-2 text-sm text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Categories Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-100 text-xs text-slate-500 uppercase tracking-wide">
                        <tr>
                            <th class="px-5 py-3.5">Categoría</th>
                            <th class="px-5 py-3.5">Categoría Padre</th>
                            <th class="px-5 py-3.5">Descripción</th>
                            <th class="px-5 py-3.5 text-center">Subcategorías</th>
                            <th class="px-5 py-3.5 text-center">Estado</th>
                            <th class="px-5 py-3.5 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-3.5 font-semibold text-slate-800">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 font-bold text-xs">
                                            <x-icon name="folder" class="w-4 h-4" />
                                        </div>
                                        <span>{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">
                                    @if($category->parent)
                                        <span
                                            class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 text-xs px-2.5 py-1 rounded-md font-medium">
                                            <x-icon name="folder" class="w-3 h-3 text-slate-400" />
                                            {{ $category->parent->name }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs italic">Principal</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 max-w-xs truncate">
                                    {{ $category->description ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="bg-indigo-50 text-indigo-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                                        {{ $category->children_count }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <form method="POST" action="{{ route('categories.toggle-status', $category) }}"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold transition {{ $category->status ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                            <span
                                                class="w-1.5 h-1.5 rounded-full {{ $category->status ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                            {{ $category->status ? 'Activo' : 'Inactivo' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button @click="openEditModal({{ json_encode($category) }})"
                                            class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                            title="Editar">
                                            <x-icon name="pencil" class="w-4 h-4" />
                                        </button>
                                        <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                            onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                                title="Eliminar">
                                                <x-icon name="trash" class="w-4 h-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <x-icon name="folder" class="w-10 h-10 text-slate-300 mx-auto mb-2" />
                                    <p class="text-sm text-slate-500 font-medium">No se encontraron categorías</p>
                                    <p class="text-xs text-slate-400 mt-1">Intenta ajustando tus filtros o crea una nueva
                                        categoría.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>

        {{-- Modal Create / Edit --}}
        <div x-show="showModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" x-transition>
            <div @click.outside="showModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-800"
                        x-text="isEditing ? 'Editar Categoría' : 'Nueva Categoría'"></h3>
                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-600">
                        <x-icon name="x" class="w-5 h-5" />
                    </button>
                </div>

                <form :action="formUrl" method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="isEditing">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Nombre
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="name" x-model="formData.name" required
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition"
                            placeholder="Ej. Cuadernos y Libretas" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Categoría
                            Padre (Opcional)</label>
                        <select name="parent_id" x-model="formData.parent_id"
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                            <option value="">Ninguna (Categoría Principal)</option>
                            @foreach($parentCategories as $parent)
                                <template x-if="formData.id != {{ $parent->id }}">
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                </template>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Descripción</label>
                        <textarea name="description" x-model="formData.description" rows="3"
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition"
                            placeholder="Descripción breve de la categoría..."></textarea>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" id="cat_status" name="status" value="1" :checked="formData.status == 1"
                            class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4" />
                        <label for="cat_status" class="text-sm text-slate-700 font-medium">Categoría Activa</label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showModal = false"
                            class="px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-xl transition">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection