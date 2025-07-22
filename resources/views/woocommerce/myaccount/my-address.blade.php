@extends('layouts.app')

@section('content')

@php
  do_action('woocommerce_before_edit_account_address_form');
@endphp

<div class="max-w-6xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-md border border-gray-200">
  <h2 class="text-2xl font-bold text-gray-800 mb-6">
    {{ __('Tus direcciones guardadas', 'woocommerce') }}
  </h2>

  <p class="text-sm text-gray-600 mb-8">
    {{ apply_filters('woocommerce_my_account_my_address_description', __('Las siguientes direcciones se usarán por defecto en la página de pago.', 'woocommerce')) }}
  </p>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @foreach ($get_addresses as $name => $address_title)
      @php
        $address = wc_get_account_formatted_address($name);
      @endphp

      <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-700">
            {{ $address_title }}
          </h3>
          <a href="{{ esc_url( wc_get_endpoint_url('edit-address', $name) ) }}"
             class="text-sm text-primary hover:underline">
            {{ $address ? sprintf(__('Editar %s', 'woocommerce'), $address_title) : sprintf(__('Añadir %s', 'woocommerce'), $address_title) }}
          </a>
        </div>

        <div class="text-sm text-gray-700 leading-relaxed">
          @if ($address)
            {!! str_replace('<br />', '<br>', $address) !!}
          @else
            <em>{{ __('Aún no has configurado este tipo de dirección.', 'woocommerce') }}</em>
          @endif
        </div>

        @php do_action('woocommerce_my_account_after_my_address', $name); @endphp
      </div>
    @endforeach
  </div>
</div>

@php
  do_action('woocommerce_after_edit_account_address_form');
@endphp
@endsection