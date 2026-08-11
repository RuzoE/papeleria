@props([
    'title' => '',
    'value' => '',
    'icon' => 'chart-bar',
    'color' => 'indigo', // indigo | emerald | amber | red | blue | purple
    'subtitle' => null,
    'link' => null,
])

@php
    $colors = [
        'indigo' => ['bg' => 'bg-indigo-50', 'icon' => 'bg-indigo-600', 'text' => 'text-indigo-600'],
        'emerald' => ['bg' => 'bg-emerald-50', 'icon' => 'bg-emerald-600', 'text' => 'text-emerald-600'],
        'amber' => ['bg' => 'bg-amber-50', 'icon' => 'bg-amber-500', 'text' => 'text-amber-600'],
        'red' => ['bg' => 'bg-red-50', 'icon' => 'bg-red-600', 'text' => 'text-red-600'],
        'blue' => ['bg' => 'bg-blue-50', 'icon' => 'bg-blue-600', 'text' => 'text-blue-600'],
        'purple' => ['bg' => 'bg-purple-50', 'icon' => 'bg-purple-600', 'text' => 'text-purple-600'],
    ];
    $c = $colors[$color] ?? $colors['indigo'];
@endphp

<div
    class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-start gap-4 hover:shadow-md transition-shadow duration-200">
    <div class="flex-shrink-0 w-12 h-12 {{ $c['icon'] }} rounded-xl flex items-center justify-center shadow-sm">
        <x-icon :name="$icon" class="w-6 h-6 text-white" />
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider truncate">{{ $title }}</p>
        <p class="text-2xl font-bold text-slate-800 mt-0.5 leading-tight">{{ $value }}</p>
        @if($subtitle)
            <p class="text-xs text-slate-400 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    @if($link)
        <a href="{{ $link }}" class="text-slate-300 hover:text-slate-500 transition flex-shrink-0 mt-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    @endif
</div>