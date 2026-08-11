@props([
    'type' => 'info',   // success | error | warning | info
    'message' => '',
    'dismissible' => false,
])

@php
    $styles = [
        'success' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-800', 'icon' => 'text-emerald-500'],
        'error' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'text' => 'text-red-800', 'icon' => 'text-red-500'],
        'warning' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'text' => 'text-amber-800', 'icon' => 'text-amber-500'],
        'info' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-800', 'icon' => 'text-blue-500'],
    ];
    $s = $styles[$type] ?? $styles['info'];

    $icons = [
        'success' => 'check-circle',
        'error' => 'x-circle',
        'warning' => 'exclamation',
        'info' => 'information-circle',
    ];
@endphp

<div {{ $dismissible ? 'x-data="{ show: true }" x-show="show"' : '' }}
    class="{{ $s['bg'] }} {{ $s['border'] }} {{ $s['text'] }} border rounded-xl px-4 py-3 flex items-start gap-3 shadow-sm mb-2">
    <x-icon :name="$icons[$type]" class="w-5 h-5 {{ $s['icon'] }} flex-shrink-0 mt-0.5" />
    <p class="flex-1 text-sm font-medium">{{ $message }}</p>
    @if($dismissible)
        <button @click="show = false"
            class="text-current opacity-50 hover:opacity-100 transition flex-shrink-0 -mt-0.5 -mr-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>