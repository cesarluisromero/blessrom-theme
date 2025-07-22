@php
    $status = $order->get_status();

    $steps = [
        'pending' => ['label' => 'Pendiente', 'color' => 'gray', 'icon' => '⏳'],
        'processing' => ['label' => 'Procesando', 'color' => 'blue', 'icon' => '🔄'],
        'completed' => ['label' => 'Completado', 'color' => 'green', 'icon' => '✅'],
        'failed' => ['label' => 'Fallido', 'color' => 'red', 'icon' => '⚠️'],
        'cancelled' => ['label' => 'Cancelado', 'color' => 'red', 'icon' => '❌'],
        'refunded' => ['label' => 'Reembolsado', 'color' => 'purple', 'icon' => '💸'],
        'on-hold' => ['label' => 'En espera', 'color' => 'yellow', 'icon' => '⏸️'],
    ];

    $mainFlow = ['pending', 'processing', 'completed']; // solo para barra de progreso

    $currentIndex = array_search($status, $mainFlow) !== false ? array_search($status, $mainFlow) : 0;
@endphp

<div 
    x-data="{ show: false }" 
    x-init="setTimeout(() => show = true, 300)" 
    x-show="show" 
    x-transition 
    class="mt-10 p-6 rounded-xl bg-green-50 border border-green-200 shadow-md text-center"
    class="w-full mt-8">
    
    <h2 class="text-lg font-semibold mb-4">Seguimiento del pedido</h2>
    <div class="flex items-center justify-between">
        @foreach ($mainFlow as $index => $step)
            @php
                $color = $steps[$step]['color'];
                $label = $steps[$step]['label'];
                $icon = $steps[$step]['icon'];
                $active = $index <= $currentIndex;
            @endphp
            <div class="flex-1 flex items-center">
                <div class="flex flex-col items-center text-xs font-medium text-center w-full">
                    <div class="w-8 h-8 flex items-center justify-center rounded-full {{ $active ? 'bg-' . $color . '-600 text-white' : 'bg-gray-300 text-gray-500' }}">
                        {{ $icon }}
                    </div>
                    <span class="mt-2 text-{{ $active ? $color . '-700' : 'gray-400' }}">{{ $label }}</span>
                </div>
                @if (!$loop->last)
                    <div class="flex-1 h-1 mx-2 {{ $active ? 'bg-' . $color . '-500' : 'bg-gray-300' }}"></div>
                @endif
            </div>
        @endforeach
    </div>
</div>

@if ($order->get_status() === 'completed')
    <div class="mt-10 p-6 rounded-xl bg-green-50 border border-green-200 shadow-md text-center">
        <div class="text-4xl mb-2">🎉</div>
        <h2 class="text-xl font-bold text-green-700">¡Tu pedido fue completado con éxito!</h2>
        <p class="text-green-600 mt-2">Gracias por tu compra. Esperamos que disfrutes tu producto.</p>

        {{-- Si más adelante quieres agregar seguimiento real, aquí podría ir --}}
        {{-- <p class="mt-4 text-sm text-gray-500">Número de seguimiento: 12345678</p> --}}

        <a href="{{ home_url('/tienda') }}"
           class="inline-block mt-6 px-6 py-2 rounded-lg bg-green-600 text-white font-medium hover:bg-green-700 transition">
            Seguir comprando
        </a>
    </div>
@endif
