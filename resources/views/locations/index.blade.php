@extends('layouts.app')

@section('title', 'Ubicaciones')
@section('page-title', 'Ubicaciones Físicas')

@section('content')

    <div x-data="{
        showModal: false,
        isEditing: false,
        formUrl: '{{ route('locations.store') }}',
        formData: { id: null, zone: '', module: '', shelf: '', level: '', position: '', description: '' },
        
        openCreateModal() {
            this.isEditing = false;
            this.formUrl = '{{ route('locations.store') }}';
            this.formData = { id: null, zone: '', module: '', shelf: '', level: '', position: '', description: '' };
            this.showModal = true;
        },

        openEditModal(location) {
            this.isEditing = true;
            this.formUrl = '/locations/' + location.id;
            this.formData = {
                id: location.id,
                zone: location.zone || '',
                module: location.module || '',
                shelf: location.shelf || '',
                level: location.level || '',
                position: location.position || '',
                description: location.description || ''
            };
            this.showModal = true;
        }
    }">

        {{-- Top Bar & Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Ubicaciones del Almacén / Tienda</h1>
                <p class="text-xs text-slate-500 mt-0.5">Control de estantes, pasillos y módulos de exhibición</p>
            </div>
            <button @click="openCreateModal()" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-xl shadow-sm transition">
                <x-icon name="plus" class="w-4 h-4" />
                Nueva Ubicación
            </button>
        </div>

        {{-- Filters & Search --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6">
            <form method="GET" action="{{ route('locations.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <x-icon name="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" />
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por zona, módulo, estante..." class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition" />
                </div>
                
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="submit" class="flex-1 sm:flex-initial bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
                        Filtrar
                    </button>
                    @if(request()->has('search'))
                        <a href="{{ route('locations.index') }}" class="px-3 py-2 text-sm text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Locations Grid/Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-100 text-xs text-slate-500 uppercase tracking-wide">
                        <tr>
                            <th class="px-5 py-3.5">Zona</th>
                            <th class="px-5 py-3.5">Módulo</th>
                            <th class="px-5 py-3.5">Estante</th>
                            <th class="px-5 py-3.5">Nivel</th>
                            <th class="px-5 py-3.5">Posición</th>
                            <th class="px-5 py-3.5">Código Completo</th>
                            <th class="px-5 py-3.5">Descripción</th>
                            <th class="px-5 py-3.5 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($locations as $location)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-3.5 font-bold text-indigo-700">
                                    <div class="flex items-center gap-2">
                                        <x-icon name="map-pin" class="w-4 h-4 text-indigo-500" />
                                        <span>{{ $location->zone }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-slate-700 font-medium">{{ $location->module ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-slate-700 font-medium">{{ $location->shelf ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-slate-700 font-medium">{{ $location->level ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-slate-700 font-medium">{{ $location->position ?? '—' }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-block bg-slate-100 text-slate-800 text-xs font-mono font-bold px-2.5 py-1 rounded-lg">
                                        {{ $location->full_code }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 max-w-xs truncate">{{ $location->description ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button @click="openEditModal({{ json_encode($location) }})" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Editar">
                                            <x-icon name="pencil" class="w-4 h-4" />
                                        </button>
                                        <form method="POST" action="{{ route('locations.destroy', $location) }}" onsubmit="return confirm('¿Seguro que deseas eliminar esta ubicación?');" class="inline">
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
                                <td colspan="8" class="px-5 py-12 text-center">
                                    <x-icon name="map-pin" class="w-10 h-10 text-slate-300 mx-auto mb-2" />
                                    <p class="text-sm text-slate-500 font-medium">No se encontraron ubicaciones</p>
                                    <p class="text-xs text-slate-400 mt-1">Crea una nueva ubicación para categorizar el espacio físico.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($locations->hasPages())
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $locations->links() }}
                </div>
            @endif
        </div>

        {{-- Modal Create / Edit --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" x-transition>
            <div @click.outside="showModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-800" x-text="isEditing ? 'Editar Ubicación' : 'Nueva Ubicación'"></h3>
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
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Zona / Área <span class="text-red-500">*</span></label>
                        <input type="text" name="zone" x-model="formData.zone" required class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition" placeholder="Ej. Pasillo A, Vitrina Central, Bodega" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Módulo</label>
                            <input type="text" name="module" x-model="formData.module" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition" placeholder="Ej. M1" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Estante</label>
                            <input type="text" name="shelf" x-model="formData.shelf" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition" placeholder="Ej. E3" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Nivel / Repisa</label>
                            <input type="text" name="level" x-model="formData.level" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition" placeholder="Ej. Nivel 2" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Posición</label>
                            <input type="text" name="position" x-model="formData.position" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition" placeholder="Ej. Izquierda" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Descripción o Notas</label>
                        <textarea name="description" x-model="formData.description" rows="2" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition" placeholder="Detalles de lo que se almacena aquí..."></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showModal = false" class="px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-xl transition">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
