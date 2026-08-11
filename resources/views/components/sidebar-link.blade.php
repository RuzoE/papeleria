@props([
    'route' => '#',
    'icon' => 'home',
    'label' => '',
    'indent' => false,
])

@php
    // Safely check if route exists before calling route()
    try {
        $url = route($route);
        $active = request()->routeIs($route) || request()->routeIs($route . '.*');
    } catch (\Exception $e) {
        $url = '#';
        $active = false;
    }
@endphp

<a href="{{ $url }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium transition-all duration-150 group
          {{ $indent ? 'ml-4' : '' }}
          {{ $active
    ? 'bg-indigo-600 text-white shadow-sm'
    : 'text-slate-400 hover:text-white hover:bg-slate-800' }}"
    :class="{ 'justify-center px-2': sidebarCollapsed && !{{ $indent ? 'true' : 'false' }} }">

    <x-icon :name="$icon"
        class="w-4 h-4 flex-shrink-0 {{ $active ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" />

    <span class="truncate" x-show="!sidebarCollapsed || window.innerWidth < 1024">{{ $label }}</span>
</a>