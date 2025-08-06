@extends('layouts.app')

@section('content')
    {{-- Headline --}}
    <p class="mb-6 text-gray-700">
        <a>Prueba</a>
        {!! sprintf(
            /* translators: 1: order number 2: date 3: status */
            __('Order %1$s was placed on %2$s and is currently %3$s.', 'woocommerce'),
            '<mark class="order-number font-semibold">#' . $order->get_order_number() . '</mark>',
            '<mark class="order-date">' . wc_format_datetime($order->get_date_created()) . '</mark>',
            '<mark class="order-status">' . wc_get_order_status_name($order->get_status()) . '</mark>'
        ) !!}
    </p>

    {{-- Order notes / updates --}}
    @if ($notes)
        <h2 class="text-lg font-bold mb-4">
            {{ __('Order updates', 'woocommerce') }}
        </h2>

        <ol class="woocommerce-OrderUpdates commentlist notes space-y-4">
            @foreach ($notes as $note)
                <li class="woocommerce-OrderUpdate comment note bg-gray-50 p-4 rounded-lg">
                    <p class="woocommerce-OrderUpdate-meta text-xs text-gray-500 mb-2">
                        {{ date_i18n(__('l jS \\o\\f F Y, h:ia', 'woocommerce'), strtotime($note->comment_date)) }}
                    </p>

                    <div class="woocommerce-OrderUpdate-description prose">
                        {!! wpautop(wptexturize($note->comment_content)) !!}
                    </div>
                </li>
            @endforeach
        </ol>
    @endif

    {{-- Hook original de WooCommerce --}}
    @php do_action('woocommerce_view_order', $order_id); @endphp
@endsection
