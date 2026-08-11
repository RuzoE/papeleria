@props([
    'label' => '',
    'icon' => 'folder',
])

<div x-data="{ open: {{ request()->routeIs(str_replace('-', '_', strtolower($label)) . '*') ? 'true' : 'false' }} }">
    <button @click="open = !open"
        class="w-full flex items-center gap-3 rounded-xl px-3 py-2 text-slate-400 hover:text-white hover:bg-slate-800 text-sm font-medium transition-all duration-150 group">
        <x-icon :name="$icon" class="w-4 h-4 flex-shrink-0 text-slate-500 group-hover:text-white" />
        <span class="flex-1 text-left truncate" x-show="!sidebarCollapsed">{{ $label }}</span>
        <svg class="w-3.5 h-3.5 transition-transform flex-shrink-0" :class="{ 'rotate-90': open }"
            x-show="!sidebarCollapsed" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>

    <div x-show="open && !sidebarCollapsed" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
        class="mt-0.5 space-y-0.5">
        {{ $slot }}
    </div>
</div>