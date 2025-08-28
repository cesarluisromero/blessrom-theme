{{-- resources/views/woocommerce/checkout/review-order.blade.php --}}
@php defined('ABSPATH') || exit; @endphp

<table class="shop_table woocommerce-checkout-review-order-table w-full text-sm">
  <thead>
    <tr class="border-b border-slate-200">
      <th class="product-name text-left py-2 font-semibold text-slate-600">
        {{ __('Product', 'woocommerce') }}
      </th>
      <th class="product-total text-right py-2 font-semibold text-slate-600">
        {{ __('Subtotal', 'woocommerce') }}
      </th>
    </tr>
  </thead>

  <tbody>
    @php do_action('woocommerce_review_order_before_cart_contents'); @endphp

    @foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
      @php
        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
      @endphp

      @if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key))
        <tr class="{!! esc_attr(apply_filters('woocommerce_cart_item_class','cart_item',$cart_item,$cart_item_key)) !!} border-b border-slate-100 last:border-0">
          <td class="product-name align-top py-3 pr-4 text-slate-800">
            {!! wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)) !!}
            <strong class="product-quantity ml-1">× {{ $cart_item['quantity'] }}</strong>

            {{-- Metadatos de variaciones / addons --}}
            <div class="mt-1 text-slate-500">
              {!! wc_get_formatted_cart_item_data($cart_item) !!}
            </div>
          </td>

          <td class="product-total align-top py-3 text-right text-slate-800 tabular-nums">
            {!! apply_filters(
              'woocommerce_cart_item_subtotal',
              WC()->cart->get_product_subtotal($_product, $cart_item['quantity']),
              $cart_item,
              $cart_item_key
            ) !!}
          </td>
        </tr>
      @endif
    @endforeach

    @php do_action('woocommerce_review_order_after_cart_contents'); @endphp
  </tbody>

  <tfoot class="text-slate-700">
    <tr class="cart-subtotal">
      <th class="py-2">{{ __('Subtotal', 'woocommerce') }}</th>
      <td class="py-2 text-right tabular-nums">{!! wc_cart_totals_subtotal_html() !!}</td>
    </tr>

    @foreach (WC()->cart->get_coupons() as $code => $coupon)
      <tr class="cart-discount coupon-{!! esc_attr(sanitize_title($code)) !!}">
        <th class="py-2">{!! wc_cart_totals_coupon_label($coupon) !!}</th>
        <td class="py-2 text-right tabular-nums">{!! wc_cart_totals_coupon_html($coupon) !!}</td>
      </tr>
    @endforeach

    @if (WC()->cart->needs_shipping() && WC()->cart->show_shipping())
      @php do_action('woocommerce_review_order_before_shipping'); @endphp
      {{-- WooCommerce imprime aquí sus propias filas <tr class="shipping"> --}}
      @php wc_cart_totals_shipping_html(); @endphp
      @php do_action('woocommerce_review_order_after_shipping'); @endphp
    @endif

    @foreach (WC()->cart->get_fees() as $fee)
      <tr class="fee">
        <th class="py-2">{{ esc_html($fee->name) }}</th>
        <td class="py-2 text-right tabular-nums">{!! wc_cart_totals_fee_html($fee) !!}</td>
      </tr>
    @endforeach

    @if (wc_tax_enabled() && ! WC()->cart->display_prices_including_tax())
      @if ('itemized' === get_option('woocommerce_tax_total_display'))
        @foreach (WC()->cart->get_tax_totals() as $code => $tax)
          <tr class="tax-rate tax-rate-{!! esc_attr(sanitize_title($code)) !!}">
            <th class="py-2">{{ esc_html($tax->label) }}</th>
            <td class="py-2 text-right tabular-nums">{!! wp_kses_post($tax->formatted_amount) !!}</td>
          </tr>
        @endforeach
      @else
        <tr class="tax-total">
          <th class="py-2">{{ esc_html(WC()->countries->tax_or_vat()) }}</th>
          <td class="py-2 text-right tabular-nums">{!! wc_cart_totals_taxes_total_html() !!}</td>
        </tr>
      @endif
    @endif

    @php do_action('woocommerce_review_order_before_order_total'); @endphp

    <tr class="order-total border-t-2 border-slate-200">
      <th class="py-3 text-base font-semibold">{{ __('Total', 'woocommerce') }}</th>
      <td class="py-3 text-right text-base font-semibold tabular-nums">
        {!! wc_cart_totals_order_total_html() !!}
      </td>
    </tr>

    @php do_action('woocommerce_review_order_after_order_total'); @endphp
  </tfoot>
</table>
