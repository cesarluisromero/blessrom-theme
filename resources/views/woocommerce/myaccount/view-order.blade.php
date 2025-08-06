{{-- resources/views/woocommerce/myaccount/view-order.blade.php --}}
@extends('layouts.app')

@php
    /**
     * Devuelve la pareja bg/text según el estado del pedido.
     * Úsalo también en otros partials si lo necesitas.
     */
    function order_status_colors(string $status): array {
        return match ($status) {
            'completed'  => ['green-100', 'green-800'],
            'processing' => ['blue-100',  'blue-800'],
            'on-hold'    => ['yellow-100','yellow-800'],
            'cancelled', 'refunded', 'failed' => ['red-100', 'red-800'],
            default      => ['slate-100','slate-800'],
        };
    }

    $status      = $order->get_status();
    [$bg, $text] = order_status_colors($status);
@endphp

@section('content')
<div class="max-w-4xl mx-auto p-6 sm:p-8 bg-white shadow-xl rounded-3xl">

    {{-- HEADER ---------------------------------------------------------------- --}}
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800">
            {{ __('Detalles del pedido', 'woocommerce') }}
        </h1>

        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold
                     bg-{{ $bg }} text-{{ $text }}">
            {{ wc_get_order_status_name($status) }}
        </span>
    </header>

    {{-- TRACKER (barra de progreso) ------------------------------------------ --}}
    <div class="mt-10">
        @include('woocommerce.myaccount.status-order')
    </div>

    {{-- RESUMEN --------------------------------------------------------------- --}}
    <section class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 text-sm text-slate-600">
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
            <p class="font-semibold text-slate-800">
                {!! wp_kses_post($order->get_formatted_order_total()) !!}
            </p>
        </div>
    </section>

    {{-- NOTAS / ACTUALIZACIONES ---------------------------------------------- --}}
    @if ($notes)
        <h2 class="mt-12 text-lg font-bold text-slate-800 mb-6">
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

    {{-- HOOK ORIGINAL --------------------------------------------------------- --}}
    @php do_action('woocommerce_view_order', $order->get_id()); @endphp
</div>
@endsection
