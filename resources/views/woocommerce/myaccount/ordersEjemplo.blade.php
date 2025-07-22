@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white rounded-xl shadow-md mt-6">
  <h2 class="text-2xl font-bold mb-6 text-gray-800">
    {{ __('Mis pedidos recientes', 'woocommerce') }}
  </h2>

  @if (have_posts())
    <table class="w-full table-auto border border-gray-200 rounded-lg overflow-hidden">
      <thead class="bg-gray-100 text-sm font-semibold text-gray-600 uppercase">
        <tr>
          <th class="px-4 py-3 text-left">{{ __('Pedido', 'woocommerce') }}</th>
          <th class="px-4 py-3 text-left">{{ __('Fecha', 'woocommerce') }}</th>
          <th class="px-4 py-3 text-left">{{ __('Estado', 'woocommerce') }}</th>
          <th class="px-4 py-3 text-left">{{ __('Total', 'woocommerce') }}</th>
          <th class="px-4 py-3 text-left">{{ __('Acciones', 'woocommerce') }}</th>
        </tr>
      </thead>
      <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
        @php
          $orders = wc_get_orders([
            'customer_id' => get_current_user_id(),
            'limit' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
          ]);
        @endphp

        @foreach ($orders as $order)
          <tr>
            <td class="px-4 py-3 font-medium text-blue-600">
              <a href="{{ esc_url($order->get_view_order_url()) }}">#{{ $order->get_order_number() }}</a>
            </td>
            <td class="px-4 py-3">
              {{ wc_format_datetime($order->get_date_created()) }}
            </td>
            <td class="px-4 py-3">
              {{ wc_get_order_status_name($order->get_status()) }}
            </td>
            <td class="px-4 py-3">
              {!! $order->get_formatted_order_total() !!}
            </td>
            <td class="px-4 py-3 space-x-2">
              @foreach (wc_get_account_orders_actions($order) as $action)
                <a href="{{ esc_url($action['url']) }}"
                   class="inline-block px-3 py-1 rounded-full text-sm font-medium text-white {{ $action['name'] === 'Pagar' ? 'bg-yellow-500' : ($action['name'] === 'Cancelar' ? 'bg-red-500' : 'bg-blue-500') }}">
                  {{ $action['name'] }}
                </a>
              @endforeach
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p class="text-gray-500">{{ __('No has realizado ningún pedido aún.', 'woocommerce') }}</p>
  @endif
</div>
@endsection
