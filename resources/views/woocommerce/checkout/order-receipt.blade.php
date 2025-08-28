{{-- resources/views/woocommerce/checkout/order-receipt.blade.php --}}
@php
  defined('ABSPATH') || exit;

  /** @var WC_Order $order */
  $order_number   = $order->get_order_number();
  $order_date     = wc_format_datetime($order->get_date_created());
  $order_total    = $order->get_formatted_order_total();
  $payment_method = $order->get_payment_method_title();
  $status_slug    = $order->get_status();
  $status_label   = wc_get_order_status_name($status_slug);
  $view_order_url = method_exists($order, 'get_view_order_url') ? $order->get_view_order_url() : '';
  $shop_url       = wc_get_page_permalink('shop');

  // Clases para el badge del estado
  $status_classes = [
    'completed' => 'bg-green-100 text-green-700',
    'processing'=> 'bg-blue-100 text-blue-700',
    'on-hold'   => 'bg-amber-100 text-amber-700',
    'pending'   => 'bg-slate-100 text-slate-700',
    'failed'    => 'bg-red-100 text-red-700',
    'cancelled' => 'bg-gray-200 text-gray-700',
    'refunded'  => 'bg-purple-100 text-purple-700',
  ];
  $badge_class = $status_classes[$status_slug] ?? 'bg-slate-100 text-slate-700';

  // Para copiar al portapapeles de forma segura
  $order_number_js = esc_js($order_number);
@endphp

{{-- Header con estado y acciones --}}
<div class="flex flex-wrap items-start justify-between gap-3 rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
  <div class="flex items-center gap-3">
    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-100">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-700" viewBox="0 0 24 24"><path fill="currentColor" d="m9.55 17.8l-4.8-4.8l1.4-1.4l3.4 3.4l7.3-7.3l1.4 1.4z"/></svg>
    </span>
    <div>
      <h2 class="text-base sm:text-lg font-semibold text-slate-800 m-0">
        {{ __('Order receipt', 'woocommerce') }}
      </h2>
      <p class="m-0 text-sm text-slate-600">
        {{ __('Thanks! This is the summary of your order.', 'woocommerce') }}
      </p>
    </div>
  </div>

  <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $badge_class }}">
    {{ $status_label }}
  </span>
</div>

{{-- Datos principales en cards (conserva la clase core order_details) --}}
<ul class="order_details grid gap-3 sm:grid-cols-2 lg:grid-cols-4 mt-4 text-sm">
  <li class="order bg-white rounded-xl border border-slate-200 p-3">
    <div class="flex items-center justify-between gap-2">
      <div>
        {{ __('Order number:', 'woocommerce') }}
        <strong>{{ esc_html($order_number) }}</strong>
      </div>
      {{-- Copiar --}}
      <button
        type="button"
        x-data="{copied:false}"
        @click="navigator.clipboard.writeText('{{ $order_number_js }}'); copied=true; setTimeout(()=>copied=false,2000)"
        class="inline-flex items-center gap-1 rounded-lg border border-slate-300 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50"
        aria-label="{{ esc_attr__('Copy order number', 'woocommerce') }}"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"><path fill="currentColor" d="M16 1H4a2 2 0 0 0-2 2v12h2V3h12z"/><path fill="currentColor" d="M20 5H8a2 2 0 0 0-2 2v14h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2"/></svg>
        <span x-show="!copied">{{ __('Copy', 'woocommerce') }}</span>
        <span x-show="copied" class="text-green-700">{{ __('Copied!', 'woocommerce') }}</span>
      </button>
    </div>
  </li>

  <li class="date bg-white rounded-xl border border-slate-200 p-3">
    {{ __('Date:', 'woocommerce') }}
    <strong>{{ esc_html($order_date) }}</strong>
  </li>

  <li class="total bg-white rounded-xl border border-slate-200 p-3">
    {{ __('Total:', 'woocommerce') }}
    <strong class="tabular-nums">{!! wp_kses_post($order_total) !!}</strong>
  </li>

  @if ($payment_method)
    <li class="method bg-white rounded-xl border border-slate-200 p-3">
      {{ __('Payment method:', 'woocommerce') }}
      <strong>{!! wp_kses_post($payment_method) !!}</strong>
    </li>
  @endif
</ul>

{{-- Acciones: Ver pedido / Seguir comprando / Imprimir --}}
<div class="mt-4 flex flex-wrap gap-3">
  @if ($view_order_url)
    <a href="{{ esc_url($view_order_url) }}"
       class="button {{ wc_wp_theme_get_element_class_name('button') ? wc_wp_theme_get_element_class_name('button') : '' }}
              inline-flex items-center justify-center rounded-xl px-4 py-2 bg-blue-600 text-white font-semibold
              hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
      {{ __('View order', 'woocommerce') }}
    </a>
  @endif

  @if ($shop_url)
    <a href="{{ esc_url($shop_url) }}"
       class="inline-flex items-center justify-center rounded-xl px-4 py-2 border border-slate-300 text-slate-800 font-medium
              hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
      {{ __('Continue shopping', 'woocommerce') }}
    </a>
  @endif

  <button type="button"
          onclick="window.print()"
          class="inline-flex items-center justify-center rounded-xl px-4 py-2 border border-slate-300 text-slate-800 font-medium
                 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
    {{ __('Print', 'woocommerce') }}
  </button>
</div>

{{-- Hook del gateway (instrucciones/QR/etc.) dentro de un contenedor estilizado --}}
<div class="mt-6 rounded-2xl border border-slate-200 bg-white p-4">
  @php do_action('woocommerce_receipt_' . $order->get_payment_method(), $order->get_id()); @endphp
</div>

<div class="clear"></div>
