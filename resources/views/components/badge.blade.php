@props([
    'value' => '',   // e.g. 'active', 'inactive', 'completed', 'cancelled', 'pending', 'agotado', 'stock_bajo'
    'label' => null, // Override auto-label
])

@php
    $map = [
        'active' => ['text' => 'Activo', 'class' => 'bg-emerald-100 text-emerald-700'],
        'inactive' => ['text' => 'Inactivo', 'class' => 'bg-slate-100 text-slate-500'],
        'completed' => ['text' => 'Completada', 'class' => 'bg-emerald-100 text-emerald-700'],
        'cancelled' => ['text' => 'Cancelada', 'class' => 'bg-red-100 text-red-700'],
        'pending' => ['text' => 'Pendiente', 'class' => 'bg-amber-100 text-amber-700'],
        'agotado' => ['text' => 'Agotado', 'class' => 'bg-red-100 text-red-700'],
        'stock_bajo' => ['text' => 'Stock Bajo', 'class' => 'bg-amber-100 text-amber-700'],
        'admin' => ['text' => 'Administrador', 'class' => 'bg-purple-100 text-purple-700'],
        'employee' => ['text' => 'Empleado', 'class' => 'bg-blue-100 text-blue-700'],
        'cashier' => ['text' => 'Cajero', 'class' => 'bg-green-100 text-green-700'],
        'viewer' => ['text' => 'Consulta', 'class' => 'bg-gray-100 text-gray-600'],
    ];
    $info = $map[$value] ?? ['text' => $value, 'class' => 'bg-slate-100 text-slate-600'];
    $display = $label ?? $info['text'];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $info['class'] }}">
    {{ $display }}
</span>