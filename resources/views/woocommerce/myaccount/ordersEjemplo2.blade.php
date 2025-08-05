@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10 bg-white rounded-2xl shadow-xl">
  <h2 class="text-3xl font-bold text-gray-900 mb-8">Mis pedidos</h2>

  @if (count($orders->orders) > 0)
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead>
          <tr class="bg-gray-100 text-left text-sm font-semibold text-gray-700">
            <th class="px-4 py-3">Pedido</th>
            <th class="px-4 py-3">Fecha</th>
            <th class="px-4 py-3">Estado</th>
            <th class="px-4 py-3">Total</th>
            <th class="px-4 py-3">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-sm text-gray-800">
          @foreach ($orders->orders as $order)
            <tr>
              <td class="px-4 py-3 font-medium text-blue-600">
                <a href="{{ esc_url($order->get_view_order_url()) }}">
                  #{{ $order->get_order_number() }}
                </a>
              </td>
              <td class="px-4 py-3">
                {{ wc_format_datetime($order->get_date_created()) }}
              </td>
              <td class="px-4 py-3 capitalize">
                {{ wc_get_order_status_name($order->get_status()) }}
              </td>
              <td class="px-4 py-3">                 
                 {{ str_replace('&nbsp;', ' ', strip_tags($order->get_formatted_order_total())) }}
              </td>
              <td class="px-4 py-3 space-x-2">
                @foreach (wc_get_account_orders_actions($order) as $action)
                  <a href="{{ esc_url($action['url']) }}"
                    class="inline-block px-3 py-1 rounded-md text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 transition">
                    {{ $action['name'] }}
                  </a>
                @endforeach
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Paginación --}}
    @if ($orders->max_num_pages > 1)
      <div class="mt-6 flex justify-center space-x-2 text-sm">
        @for ($i = 1; $i <= $orders->max_num_pages; $i++)
          <a href="{{ esc_url(add_query_arg('paged', $i)) }}"
            class="px-3 py-1 border rounded {{ $i === $current_page ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }}">
            {{ $i }}
          </a>
        @endfor
      </div>
    @endif
  @else
    <p class="text-gray-600">No tienes pedidos aún.</p>
  @endif
</div>
@endsection
