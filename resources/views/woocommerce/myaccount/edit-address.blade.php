@extends('layouts.app')

@section('content')
<div class="bg-[#F0F0F0] py-10 min-h-screen">
  <div class="max-w-5xl mx-auto px-4">
    <h1 class="text-3xl font-bold mb-10 text-gray-800 text-center">📍 Mis direcciones</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      {{-- Dirección de facturación --}}
      <div class="bg-white rounded-2xl shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-3">Dirección de facturación</h2>

        @php
          $billing = function_exists('wc_get_account_address') ? wc_get_account_address('billing') : '';
        @endphp
        <div class="text-gray-600 text-sm min-h-[80px]">
          {!! wp_kses_post($billing ?: '<em>No has configurado esta dirección aún.</em>') !!}
        </div>

        <a 
          href="{{ esc_url(wc_get_endpoint_url('edit-address', 'billing')) }}"
          class="inline-flex items-center gap-2 mt-4 text-sm text-blue-600 hover:text-blue-800 font-medium transition"
        >
          <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600">
            ✏️
          </span>
          Editar dirección de facturación
        </a>
      </div>

      {{-- Dirección de envío --}}
      <div class="bg-white rounded-2xl shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-3">Dirección de envío</h2>

        @php
          $shipping = function_exists('wc_get_account_address') ? wc_get_account_address('shipping') : '';
        @endphp
        <div class="text-gray-600 text-sm min-h-[80px]">
          {!! wp_kses_post($shipping ?: '<em>No has configurado esta dirección aún.</em>') !!}
        </div>

        <a 
          href="{{ esc_url(wc_get_endpoint_url('edit-address', 'shipping')) }}"
          class="inline-flex items-center gap-2 mt-4 text-sm text-blue-600 hover:text-blue-800 font-medium transition"
        >
          <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600">
            ✏️
          </span>
          Editar dirección de envío
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
