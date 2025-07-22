@extends('layouts.app')

@section('content')
@php
  do_action('woocommerce_before_edit_account_address_form');
@endphp

@if (! $load_address)
  @php wc_get_template( 'myaccount/my-address.php' ); @endphp
@else
  <div class="max-w-3xl mx-auto mt-10 bg-white border border-gray-200 shadow rounded-xl p-8">

    <h2 class="text-2xl font-bold text-gray-800 mb-6">
      {{ $page_title }}
    </h2>

    <form method="post" class="space-y-6" novalidate>
      @php do_action("woocommerce_before_edit_address_form_{$load_address}") @endphp

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($address as $key => $field)
          <div class="col-span-1">
            {!! woocommerce_form_field($key, $field, wc_get_post_data_by_key($key, $field['value'])) !!}
          </div>
        @endforeach
      </div>

      @php do_action("woocommerce_after_edit_address_form_{$load_address}") @endphp

      <div class="pt-4">
        <button
          type="submit"
          name="save_address"
          value="{{ __('Save address', 'woocommerce') }}"
          class="bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-4 rounded-lg transition w-full md:w-auto"
        >
          {{ __('Save address', 'woocommerce') }}
        </button>

        @php
          wp_nonce_field('woocommerce-edit_address', 'woocommerce-edit-address-nonce');
        @endphp

        <input type="hidden" name="action" value="edit_address" />
      </div>
    </form>
  </div>
@endif

@php do_action('woocommerce_after_edit_account_address_form') @endphp

@endsection

