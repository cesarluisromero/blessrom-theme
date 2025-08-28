<div class="bg-white p-6 rounded-xl shadow space-y-4 text-sm text-gray-800">
    <h2 class="text-lg font-semibold text-gray-700">Resumen del pedido</h2>

    <hr class="border-gray-200">

    <table class="w-full text-left">
        <thead class="text-xs text-gray-500 uppercase">
            <tr>
                <th class="pb-2">Producto</th>
                <th class="pb-2 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
                @php
                    /** @var WC_Product $product */
                    $product  = $cart_item['data'];
                    $qty      = $cart_item['quantity'];
                    // Miniatura (ocúltala en XS si quieres con "hidden sm:block")
                    $thumb    = $product->get_image('woocommerce_thumbnail', [
                        'class' => 'w-12 h-12 md:w-16 md:h-16 rounded object-cover shrink-0'
                    ]);
                    $link     = $product->is_visible() ? get_permalink($product->get_id()) : '';
                @endphp

                <tr>
                    <td class="py-3">
                        <div class="flex items-start gap-3">
                            {!! $link ? '<a href="'.esc_url($link).'" class="block">'.$thumb.'</a>' : $thumb !!}
                            <div class="min-w-0">
                                <div class="font-medium">
                                    {!! $link
                                        ? '<a href="'.esc_url($link).'">'.esc_html($product->get_name()).'</a>'
                                        : esc_html($product->get_name()) !!}
                                </div>

                                {{-- Atributos y cantidad --}}
                                <div class="text-xs text-gray-500">
                                    @if ($product->is_type('variation') && !empty($cart_item['variation']))
                                        @foreach ($cart_item['variation'] as $key => $value)
                                            {!! wc_attribute_label(str_replace('attribute_', '', $key)) !!}: {!! esc_html(wc_clean(wp_unslash($value))) !!} <br>
                                        @endforeach
                                    @endif
                                    × {{ $qty }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 text-right">
                        {!! WC()->cart->get_product_subtotal($product, $qty) !!}
                    </td>
                </tr>
            @endforeach

        </tbody>
        <tfoot class="border-t border-gray-300 text-sm">
            <tr>
                <th class="pt-4 text-right font-normal">Subtotal</th>
                <td class="pt-4 text-right">{!! WC()->cart->get_cart_subtotal() !!}</td>
            </tr>
            @foreach (WC()->cart->get_coupons() as $code => $coupon)
                <tr>
                    <th class="text-right font-normal">Cupón: {{ esc_html($coupon->get_code()) }}</th>
                    <td class="text-right">{!! wc_cart_totals_coupon_html($coupon) !!}</td>
                </tr>
            @endforeach
            @if (WC()->cart->needs_shipping())
                <tr>
                    <th class="text-right font-normal">Envío</th>
                    <td class="text-right">{!! WC()->cart->get_cart_shipping_total() !!}</td>
                </tr>
            @endif
            <tr>
                <th class="pt-4 text-right text-base font-semibold">Total</th>
                <td class="pt-4 text-right text-base font-bold">{!! WC()->cart->get_total() !!}</td>
            </tr>
        </tfoot>
    </table>
</div>
