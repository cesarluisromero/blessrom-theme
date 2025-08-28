{{-- resources/views/woocommerce/checkout/order-receipt.blade.php --}}
@php defined('ABSPATH') || exit; @endphp

<ul class="order_details grid gap-3 sm:grid-cols-2 lg:grid-cols-4 mt-4 text-sm">
  <li class="order bg-white rounded-xl border border-slate-200 p-3">
    {{ __('Order number:', 'woocommerce') }}
    <strong>{{ esc_html($order->get_order_number()) }}</strong>
  </li>

  <li class="date bg-white rounded-xl border border-slate-200 p-3">
    {{ __('Date:', 'woocommerce') }}
    <strong>{{ esc_html(wc_format_datetime($order->get_date_created())) }}</strong>
  </li>

  <li class="total bg-white rounded-xl border border-slate-200 p-3">
    {{ __('Total:', 'woocommerce') }}
    <strong>{!! wp_kses_post($order->get_formatted_order_total()) !!}</strong>
  </li>

  @if ($order->get_payment_method_title())
    <li class="method bg-white rounded-xl border border-slate-200 p-3">
      {{ __('Payment method:', 'woocommerce') }}
      <strong>{!! wp_kses_post($order->get_payment_method_title()) !!}</strong>
    </li>
  @endif
</ul>

@php do_action('woocommerce_receipt_' . $order->get_payment_method(), $order->get_id()); @endphp

<div class="clear"></div>
