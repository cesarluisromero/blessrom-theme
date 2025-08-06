@php
    // 1) Definición de cada estado
    $steps = [
        'pending'    => ['label' => __('Pendiente', 'woocommerce'),   'bg' => 'bg-gray-400',   'text' => 'text-gray-700',  'icon' => '⏳'],
        'processing' => ['label' => __('Procesando', 'woocommerce'),  'bg' => 'bg-blue-500',   'text' => 'text-blue-700',  'icon' => '🔄'],
        'completed'  => ['label' => __('Completado', 'woocommerce'),  'bg' => 'bg-green-500',  'text' => 'text-green-700', 'icon' => '✅'],
        'on-hold'    => ['label' => __('En espera', 'woocommerce'),   'bg' => 'bg-yellow-400', 'text' => 'text-yellow-700','icon' => '⏸️'],
        'cancelled'  => ['label' => __('Cancelado', 'woocommerce'),   'bg' => 'bg-red-500',    'text' => 'text-red-700',   'icon' => '❌'],
        'failed'     => ['label' => __('Fallido', 'woocommerce'),     'bg' => 'bg-red-500',    'text' => 'text-red-700',   'icon' => '⚠️'],
        'refunded'   => ['label' => __('Reembolsado', 'woocommerce'), 'bg' => 'bg-purple-500', 'text' => 'text-purple-700','icon' => '💸'],
    ];

    $status = $order->get_status();

    // 2) Define el flujo principal - también será el orden de la barra
    $flow = ['pending', 'processing', 'completed'];

    // Si el estado actual NO está en el flujo principal (ej. cancelado):
    if (! in_array($status, $flow, true)) {
        $flow = [$status];          // muestra un solo paso (icono + label)
    }

    $currentIndex = array_search($status, $flow, true);
@endphp


{{-- CONTENEDOR animado con Alpine (opcional) --}}
<div
    x-data="{ show: false }"
    x-init="setTimeout(() => show = true, 250)"
    x-show="show"
    x-transition.opacity.scale
    class="mt-10 p-6 rounded-2xl bg-slate-50 border border-slate-200 shadow-sm text-center"
>
    <h2 class="text-lg font-semibold mb-6">
        {{ __('Seguimiento del pedido', 'woocommerce') }}
    </h2>

    {{-- BARRA / TIMELINE --}}
    <div class="flex items-center justify-between max-w-xl mx-auto">
        @foreach ($flow as $index => $step)
            @php
                $active = $index <= $currentIndex;
                $s      = $steps[$step];
            @endphp

            <div class="flex flex-col items-center flex-1">
                {{-- Icono circular --}}
                <div class="w-9 h-9 flex items-center justify-center rounded-full
                            {{ $active ? $s['bg'].' text-white' : 'bg-slate-300 text-slate-600' }}">
                    {{ $s['icon'] }}
                </div>

                {{-- Etiqueta --}}
                <span class="mt-2 text-xs font-medium
                             {{ $active ? $s['text'] : 'text-slate-400' }}">
                    {{ $s['label'] }}
                </span>
            </div>

            {{-- Barra de conexión (salvo último) --}}
            @if (! $loop->last)
                <div class="flex-1 h-1 mx-1 sm:mx-3
                            {{ $active ? $s['bg'] : 'bg-slate-300' }}">
                </div>
            @endif
        @endforeach
    </div>
</div>


{{-- MENSAJE DE PEDIDO COMPLETADO --------------------------------------------- --}}
@if ($status === 'completed')
    <div class="mt-10 p-6 rounded-2xl bg-green-50 border border-green-200 text-center shadow-sm">
        <div class="text-5xl mb-2">🎉</div>
        <h2 class="text-xl font-bold text-green-700 mb-1">
            {{ __('¡Tu pedido fue completado con éxito!', 'woocommerce') }}
        </h2>
        <p class="text-green-600">
            {{ __('Gracias por tu compra. Esperamos que disfrutes tu producto.', 'woocommerce') }}
        </p>

        <a href="{{ wc_get_page_permalink('shop') }}"
           class="inline-block mt-6 px-6 py-2 rounded-lg bg-green-600 text-white font-medium hover:bg-green-700 transition">
            {{ __('Seguir comprando', 'woocommerce') }}
        </a>
    </div>
@endif
