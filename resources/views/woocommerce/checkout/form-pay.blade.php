{{-- resources/views/woocommerce/checkout/form-pay.blade.php --}}
@php defined('ABSPATH') || exit; @endphp

<form id="order_review" method="post">
  <table class="shop_table w-full text-sm">
    <thead>
      <tr>
        <th class="product-name">{{ __('Product', 'woocommerce') }}</th>
        <th class="product-quantity">{{ __('Qty', 'woocommerce') }}</th>
        <th class="product-total">{{ __('Totals', 'woocommerce') }}</th>
      </tr>
    </thead>

    <tbody>
      @if (count($order->get_items()) > 0)
        @foreach ($order->get_items() as $item_id => $item)
          @if (apply_filters('woocommerce_order_item_visible', true, $item))
            <tr class="{{ esc_attr(apply_filters('woocommerce_order_item_class', 'order_item', $item, $order)) }}">
              <td class="product-name">
                {!! wp_kses_post(apply_filters('woocommerce_order_item_name', $item->get_name(), $item, false)) !!}

                @php do_action('woocommerce_order_item_meta_start', $item_id, $item, $order, false); @endphp
                @php wc_display_item_meta($item); @endphp
                @php do_action('woocommerce_order_item_meta_end', $item_id, $item, $order, false); @endphp
              </td>

              <td class="product-quantity">
                {!! apply_filters(
                  'woocommerce_order_item_quantity_html',
                  ' <strong class="product-quantity">' . sprintf('&times;&nbsp;%s', esc_html($item->get_quantity())) . '</strong>',
                  $item
                ) !!}
              </td>

              <td class="product-subtotal">
                {!! $order->get_formatted_line_subtotal($item) !!}
              </td>
            </tr>
          @endif
        @endforeach
      @endif
    </tbody>

    <tfoot>
      @if (!empty($totals))
        @foreach ($totals as $total)
          <tr>
            <th scope="row" colspan="2">{!! $total['label'] !!}</th>
            <td class="product-total">{!! $total['value'] !!}</td>
          </tr>
        @endforeach
      @endif
    </tfoot>
  </table>

  @php
    /**
     * Triggered from within the checkout/form-pay.php template, immediately before the payment section.
     *
     * @since 8.2.0
     */
    do_action('woocommerce_pay_order_before_payment');
  @endphp

  <div id="payment" class="woocommerce-checkout-payment mt-4">
    @if ($order->needs_payment())
      <ul class="wc_payment_methods payment_methods methods">
        @if (!empty($available_gateways))
          @foreach ($available_gateways as $gateway)
            @php wc_get_template('checkout/payment-method.php', ['gateway' => $gateway]); @endphp
          @endforeach
        @else
          <li>
            @php
              wc_print_notice(
                apply_filters(
                  'woocommerce_no_available_payment_methods_message',
                  __('Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce')
                ),
                'notice'
              );
            @endphp
          </li>
        @endif
      </ul>
    @endif

    <div class="form-row mt-3">
      <input type="hidden" name="woocommerce_pay" value="1" />

      @php wc_get_template('checkout/terms.php'); @endphp

      @php do_action('woocommerce_pay_order_before_submit'); @endphp

      {!! apply_filters(
        'woocommerce_pay_order_button_html',
        '<button type="submit" class="button alt' .
          ( wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : '' ) .
          '" id="place_order" value="' . esc_attr($order_button_text) .
          '" data-value="' . esc_attr($order_button_text) . '">' .
          esc_html($order_button_text) .
        '</button>'
      ) !!}

      @php do_action('woocommerce_pay_order_after_submit'); @endphp

      @php wp_nonce_field('woocommerce-pay', 'woocommerce-pay-nonce'); @endphp
    </div>
  </div>
</form>
