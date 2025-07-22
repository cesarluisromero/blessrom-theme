{{-- resources/views/woocommerce/myaccount/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
  @php
    $user = wp_get_current_user();
    $logout_url = esc_url(wc_logout_url());
    $orders_url = wc_get_endpoint_url('orders');
    $edit_account_url = wc_get_endpoint_url('edit-account');
    $edit_address_url = wc_get_endpoint_url('edit-address');
    $address = wc_get_account_formatted_address('billing');

    $customer = new WC_Customer($user->ID);
    $order_count = wc_get_customer_order_count($user->ID);
  @endphp

<div class="max-w-6xl mx-auto mt-10 p-6">
  <h2 class="text-2xl font-bold text-gray-800 mb-6">Hola, <span class="text-primary">{{ $user->display_name }}</span> 👋</h2>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    {{-- Card: Pedidos --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow p-6">
      <div class="text-sm text-gray-500 mb-2">Pedidos realizados</div>
      <div class="text-3xl font-bold text-primary">{{ $order_count }}</div>
      <a href="{{ $orders_url }}" class="text-sm mt-3 inline-block text-primary hover:underline">
        Ver pedidos recientes →
      </a>
    </div>

    {{-- Card: Dirección --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow p-6">
      <div class="text-sm text-gray-500 mb-2">Dirección principal</div>
      <div class="text-sm text-gray-700 leading-relaxed">
       
        {{ str_replace(['<br/>', '<br>', '<br />'], ', ', $address ?: __('No has configurado tu dirección aún.', 'woocommerce')) }}


      </div>
      <a href="{{ $edit_address_url }}" class="text-sm mt-3 inline-block text-primary hover:underline">
        Editar dirección →
      </a>
    </div>

    {{-- Card: Cuenta --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow p-6">
      <div class="text-sm text-gray-500 mb-2">Cuenta</div>
      <div class="text-sm text-gray-700">Correo: <strong>{{ $user->user_email }}</strong></div>
      <div class="flex items-center justify-between mt-3">
        <a href="{{ $edit_account_url }}" class="text-sm text-primary hover:underline">Editar cuenta</a>
        @php
          $logout_html = sprintf(
            wp_kses(
              __('<a class="underline text-red-600 hover:text-red-700" href="%2$s">Cerrar sesión</a>', 'woocommerce'),
              ['a' => ['href' => [], 'class' => []]]
            ),
            '<strong>' . esc_html($user->display_name) . '</strong>',
            esc_url($logout_url)
          );
        @endphp

        
          {!! $logout_html !!}
        
      </div>
    </div>

  </div>

  <div class="mt-10 text-sm text-gray-600 leading-relaxed">
    @php
      do_action('woocommerce_account_dashboard');
      do_action('woocommerce_before_my_account');
      do_action('woocommerce_after_my_account');
    @endphp
  </div>
</div>


@endsection
