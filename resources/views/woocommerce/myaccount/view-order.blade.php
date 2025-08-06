@extends('layouts.app')

@section('content')
    <div class="container max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-bold mb-4">Detalles del pedido</h1>
        {{-- Headline --}}
        <p class="mb-6 text-gray-700">
        
            {!! sprintf(
                /* translators: 1: order number 2: date 3: status */
                __('Orden %1$s fue creado el %2$s y su estado es %3$s.', 'woocommerce'),
                '<mark class="order-number font-semibold">#' . $order->get_order_number() . '</mark>',
                '<mark class="order-date">' . wc_format_datetime($order->get_date_created()) . '</mark>',
                '<mark class="order-status">' . wc_get_order_status_name($order->get_status()) . '</mark>'
            ) !!}
        </p>

        {{-- Order notes / updates --}}
        @if ($notes)
            <h2 class="font-bold text-lg mb-2">
                {{ __('Order updates', 'woocommerce') }}
            </h2>

            <ol class="woocommerce-OrderUpdates commentlist notes space-y-4">
                @foreach ($notes as $note)
                    <li class="woocommerce-OrderUpdate comment note flex justify-between border-b py-2">
                        <p class="woocommerce-OrderUpdate-meta font-semibold text-sm text-gray-600">
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
    </div>
@endsection
