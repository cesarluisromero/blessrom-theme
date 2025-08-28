{{-- resources/views/woocommerce/checkout/thankyou.blade.php --}}
@php defined('ABSPATH') || exit; @endphp

<div class="woocommerce-order">

  @if ($order)
    @php do_action('woocommerce_before_thankyou', $order->get_id()); @endphp

    @if ($order->has_status('failed'))
      <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
        {{ __('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce') }}
      </p>

      <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions flex gap-3">
        <a href="{{ esc_url($order->get_checkout_payment_url()) }}"
           class="button pay rounded-xl px-4 py-2">
          {{ __('Pay', 'woocommerce') }}
        </a>

        @if (is_user_logged_in())
          <a href="{{ esc_url(wc_get_page_permalink('myaccount')) }}"
             class="button pay rounded-xl px-4 py-2">
            {{ __('My account', 'woocommerce') }}
          </a>
        @endif
      </p>

    @else
      {{-- Mensaje "Order received" estándar de Woo --}}
      @php wc_get_template('checkout/order-received.php', ['order' => $order]); @endphp

      {{-- Resumen del pedido --}}
      <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details grid gap-3 sm:grid-cols-2 lg:grid-cols-3 mt-4 text-sm">
        <li class="woocommerce-order-overview__order order bg-white rounded-xl border border-slate-200 p-3">
          {{ __('Order number:', 'woocommerce') }}
          <strong>{!! $order->get_order_number() !!}</strong>
        </li>

        <li class="woocommerce-order-overview__date date bg-white rounded-xl border border-slate-200 p-3">
          {{ __('Date:', 'woocommerce') }}
          <strong>{!! wc_format_datetime($order->get_date_created()) !!}</strong>
        </li>

        @if (is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email())
          <li class="woocommerce-order-overview__email email bg-white rounded-xl border border-slate-200 p-3">
            {{ __('Email:', 'woocommerce') }}
            <strong>{!! $order->get_billing_email() !!}</strong>
          </li>
        @endif

        <li class="woocommerce-order-overview__total total bg-white rounded-xl border border-slate-200 p-3">
          {{ __('Total:', 'woocommerce') }}
          <strong>{!! $order->get_formatted_order_total() !!}</strong>
        </li>

        @if ($order->get_payment_method_title())
          <li class="woocommerce-order-overview__payment-method method bg-white rounded-xl border border-slate-200 p-3">
            {{ __('Payment method:', 'woocommerce') }}
            <strong>{!! wp_kses_post($order->get_payment_method_title()) !!}</strong>
          </li>
        @endif
      </ul>
    @endif

    @php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); @endphp
    @php do_action('woocommerce_thankyou', $order->get_id()); @endphp

  @else
    {{-- Sin objeto $order (fallback estándar) --}}
    @php wc_get_template('checkout/order-received.php', ['order' => false]); @endphp
  @endif

</div>
