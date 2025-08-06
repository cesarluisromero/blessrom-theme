@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 sm:p-8 bg-white shadow-xl rounded-3xl">
    
        
    
    {{-- CABECERA --------------------------------------------------------------- --}}
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
        @include('woocommerce.myaccount.status-order')
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800">
            {{ __('Detalles del pedido', 'woocommerce') }}
        </h1>

        {{-- Badge de estado dinámico --}}
        @php
            $status = $order->get_status();
            $color  = match ($status) {
                'completed'  => 'green',
                'processing' => 'blue',
                'on-hold'    => 'yellow',
                'cancelled', 'refunded', 'failed' => 'red',
                default      => 'slate',
            };
        @endphp
        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold
                     bg-{{ $color }}-100 text-{{ $color }}-800">
            {{ wc_get_order_status_name($status) }}
        </span>
    </header>

    {{-- RESUMEN --------------------------------------------------------------- --}}
    <section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 text-sm text-slate-600 mb-10">
        <div>
            <p class="font-medium text-slate-500">{{ __('Pedido #', 'woocommerce') }}</p>
            <p class="font-semibold text-slate-800">#{{ $order->get_order_number() }}</p>
        </div>

        <div>
            <p class="font-medium text-slate-500">{{ __('Fecha', 'woocommerce') }}</p>
            <p class="font-semibold text-slate-800">
                {{ wc_format_datetime($order->get_date_created()) }}
            </p>
        </div>

        <div>
            <p class="font-medium text-slate-500">{{ __('Total', 'woocommerce') }}</p>
            {{-- ⚠️ Imprimimos sin escapar para que aparezca el HTML nativo de Woo --}}
            <p class="font-semibold text-slate-800">
                {!! wp_kses_post($order->get_formatted_order_total()) !!}
            </p>
        </div>
    </section>

    {{-- ACTUALIZACIONES / NOTAS ---------------------------------------------- --}}
    @if ($notes)
        <h2 class="text-lg font-bold text-slate-800 mb-6">
            {{ __('Actualizaciones del pedido', 'woocommerce') }}
        </h2>

        <ol class="relative border-s border-slate-200 space-y-8 pl-6">
            @foreach ($notes as $note)
                <li class="relative">
                    <span class="absolute -left-[7px] top-1.5 h-3 w-3 rounded-full bg-slate-400"></span>

                    <time
                        datetime="{{ esc_attr($note->comment_date) }}"
                        class="block text-xs font-medium text-slate-500 mb-1"
                    >
                        {{ date_i18n(__('j M Y, g:ia', 'woocommerce'), strtotime($note->comment_date)) }}
                    </time>

                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 prose prose-sm max-w-none">
                        {!! wpautop(wptexturize($note->comment_content)) !!}
                    </div>
                </li>
            @endforeach
        </ol>
    @endif

    {{-- Hook original de WooCommerce ----------------------------------------- --}}
    @php do_action('woocommerce_view_order', $order_id); @endphp
</div>
@endsection
