@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto p-6 bg-white rounded-xl shadow-md mt-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            {{ __('Mis pedidos recientes', 'woocommerce') }}
        </h2>
        @php
            do_action( 'woocommerce_before_account_orders', $has_orders ); 
        @endphp

        @if ($has_orders)
            <table class="w-full table-auto border border-gray-200 rounded-lg overflow-hidden woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
                <thead class="bg-gray-100 text-sm font-semibold text-gray-600 uppercase">
                    <tr>
                        @foreach ( wc_get_account_orders_columns() as $column_id => $column_name )
                            <th
                                scope="col"
                                class="px-4 py-3 text-left woocommerce-orders-table__header woocommerce-orders-table__header-{{ esc_attr($column_id) }}"
                            >
                                <span class="nobr">{{ esc_html($column_name) }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @foreach ($customer_orders->orders as $customer_order)
                        @php
                            $order      = wc_get_order($customer_order);
                            $item_count = $order->get_item_count() - $order->get_item_count_refunded();
                        @endphp

                        <tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-{{ esc_attr($order->get_status()) }} order">
                            @foreach (wc_get_account_orders_columns() as $column_id => $column_name)
                                @php $is_order_number = $column_id === 'order-number'; @endphp

                                {{-- celda th/td según la columna --}}
                                @if ($is_order_number)
                                    <th
                                        class="px-4 py-3 font-medium text-gray-900 woocommerce-orders-table__cell woocommerce-orders-table__cell-{{ esc_attr($column_id) }}"
                                        data-title="{{ esc_attr($column_name) }}"
                                        scope="row"
                                    >
                                @else
                                    <td
                                        class="px-4 py-3 font-medium text-amber-600 woocommerce-orders-table__cell woocommerce-orders-table__cell-{{ esc_attr($column_id) }}"
                                        data-title="{{ esc_attr($column_name) }}"
                                    >
                                @endif

                                    {{-- hook para columnas personalizadas --}}
                                    @php
                                        do_action("woocommerce_my_account_my_orders_column_{$column_id}", $order);
                                    @endphp

                                    {{-- columnas por defecto --}}
                                    @if ($is_order_number)
                                        <a  href="{{ esc_url($order->get_view_order_url()) }}"
                                            aria-label="{{ esc_attr(sprintf(__('View order number %s', 'woocommerce'), $order->get_order_number())) }}"
                                        >
                                            {{ _x('#', 'hash before order number', 'woocommerce') . $order->get_order_number() }}
                                        </a>

                                    @elseif ($column_id === 'order-date')
                                        <time datetime="{{ esc_attr($order->get_date_created()->date('c')) }}">
                                            {{ wc_format_datetime($order->get_date_created()) }}
                                        </time>

                                    @elseif ($column_id === 'order-status')
                                        {{ wc_get_order_status_name($order->get_status()) }}

                                    @elseif ($column_id === 'order-total')
                                        {!! sprintf(
                                            _n('%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce'),
                                            $order->get_formatted_order_total(),
                                            $item_count
                                        ) !!}

                                    @elseif ($column_id === 'order-actions')
                                        @php $actions = wc_get_account_orders_actions($order); @endphp
                                        @foreach ($actions as $key => $action)
                                            @php
                                                $action_aria = $action['aria-label'] ?? sprintf(
                                                    __('%1$s order number %2$s', 'woocommerce'),
                                                    $action['name'],
                                                    $order->get_order_number()
                                                );
                                            @endphp
                                            <a  href="{{ esc_url($action['url']) }}"
                                                class="woocommerce-button {{ esc_attr($wp_button_class) }} button {{ sanitize_html_class($key) }}"
                                                aria-label="{{ esc_attr($action_aria) }}"
                                            >
                                                {{ $action['name'] }}
                                            </a>
                                        @endforeach
                                    @endif

                                {{-- cierre de celda --}}
                                @if ($is_order_number)
                                    </th>
                                @else
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>

            
            
            </table>
            @php 
                do_action( 'woocommerce_before_account_orders_pagination' );
            @endphp

            @if ($customer_orders->max_num_pages > 1)
                <div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
                    {{-- Botón «Previous» --}}
                    @if ($current_page !== 1)
                        <a
                            class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button{{ esc_attr($wp_button_class) }}"
                            href="{{ esc_url(wc_get_endpoint_url('orders', $current_page - 1)) }}"
                        >
                            {{ __('Previous', 'woocommerce') }}
                        </a>
                    @endif

                    {{-- Botón «Next» --}}
                    @if ((int) $customer_orders->max_num_pages !== $current_page)
                        <a
                            class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button{{ esc_attr($wp_button_class) }}"
                            href="{{ esc_url(wc_get_endpoint_url('orders', $current_page + 1)) }}"
                        >
                            {{ __('Next', 'woocommerce') }}
                        </a>
                    @endif
                </div>
            @endif


        @else
            @php
                wc_print_notice(
                    __('No order has been made yet.', 'woocommerce') .
                    ' <a class="woocommerce-Button wc-forward button' . esc_attr($wp_button_class) . '" href="' .
                    esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))) .
                    '">' . __('Browse products', 'woocommerce') . '</a>',
                    'notice'   // tipo de mensaje
                );
            @endphp
        @endif
        @php do_action('woocommerce_after_account_orders', $has_orders); @endphp
    </div>
@endsection