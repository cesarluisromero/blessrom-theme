{{-- resources/views/woocommerce/checkout/review-order.blade.php --}}
@php defined('ABSPATH') || exit; @endphp

<table class="shop_table woocommerce-checkout-review-order-table w-full text-sm">
  <thead>
    <tr class="border-b border-slate-200">
      <th class="product-name text-left py-2 font-semibold text-slate-600">{{ __('Product', 'woocommerce') }}</th>
      <th class="product-total text-right py-2 font-semibold text-slate-600">{{ __('Subtotal', 'woocommerce') }}</th>
    </tr>
  </thead>

  <tbody>
    @php do_action('woocommerce_review_order_before_cart_contents'); @endphp

    @foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
      @php $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key); @endphp

      @if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key))
        <tr class="{{ esc_attr(apply_filters('woocommerce_cart_item_class','cart_item',$cart_item,$cart_item_key)) }} border-b border-slate-100 last:border-0">
          <td class="product-name align-top py-3 pr-4 text-slate-800">
            {!! wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)) !!}
            <strong class="product-quantity ml-1">× {{ $cart_item['quantity'] }}</strong>
            <div class="mt-1 text-slate-500">
              {!! wc_get_formatted_cart_item_data($cart_item) !!}
            </div>
          </td>
          <td class="product-total align-top py-3 text-right text-slate-800 tabular-nums">
            {!! apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key) !!}
          </td>
        </tr>
      @endif
    @endforeach

    @php do_action('woocommerce_review_order_after_cart_contents'); @endphp
  </tbody>

  <tfoot class="text-slate-700">
    <tr class="cart-subtotal">
      <th class="py-2">{{ __('Subtotal', 'woocommerce') }}</th>
      <td class="py-
