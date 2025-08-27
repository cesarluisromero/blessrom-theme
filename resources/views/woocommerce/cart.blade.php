<div class="container max-w-6xl mx-auto p-6 bg-white shadow rounded-lg">
    <h1 class="text-2xl font-bold mb-6">Tu carrito</h1>

    @if (WC()->cart->is_empty())
        <p class="text-gray-600">Tu carrito está vacío.</p>
    @else
        <form action="{{ wc_get_cart_url() }}" method="post">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-[65%_35%] gap-6">
                {{-- Lista de productos --}}
                <div class="space-y-4">
                    @foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
                        @php
                            $product = $cart_item['data'];
                            $product_permalink = $product->is_visible() ? $product->get_permalink($cart_item) : '';
                            $max_stock = $product->get_stock_quantity() - $cart_item['quantity'];
                        @endphp

                        <pre>{{ print_r($max_stock, true) }}</pre>
                        <div class="flex gap-4 border rounded p-4 items-center">
                            <img src="{{ wp_get_attachment_image_url($product->get_image_id(), 'thumbnail') }}" class="w-20 h-20 object-cover rounded border">
                            <div class="flex-grow">
                                <a href="{{ $product_permalink }}" class="text-lg font-semibold text-gray-800 hover:underline">{{ $product->get_name() }}</a>
                                <label class="text-sm text-gray-500 mt-1 block" for="qty_{{ $cart_item_key }}">Cantidad:</label>
                                <input
                                    id="qty_{{ $cart_item_key }}"
                                    type="number"
                                    name="cart[{{ $cart_item_key }}][qty]"
                                    value="{{ $cart_item['quantity'] }}"
                                    min="1"
                                    onkeydown="return false;" 
                                    max="{{ $product->get_stock_quantity() }}"
                                    class="w-20 border border-gray-300 text-sm rounded px-2 py-1 mt-1"
                                />
                            </div>
                            <div class="text-right">
                                <p class="text-gray-800 font-bold">S/. {{ $product->get_price() }}</p>

                                {{-- Eliminar producto --}}
                                <form method="post" action="{{ wc_get_cart_url() }}" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="cart_item_key" value="{{ $cart_item_key }}">
                                    <button
                                        type="submit"
                                        name="remove_item"
                                        class="text-sm text-red-600 hover:underline"
                                    >
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Resumen del pedido --}}
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 space-y-4">
                    <h2 class="text-xl font-semibold mb-2">Total</h2>

                    @php
                        $subtotal = WC()->cart->get_subtotal();
                        $total = WC()->cart->get_total('edit');
                        $ahorro = floatval($subtotal) - floatval(WC()->cart->get_cart_contents_total());
                    @endphp

                    @if ($ahorro > 0)
                        <p class="text-sm text-red-600 font-semibold">¡Has ahorrado S/. {{ number_format($ahorro, 2) }} !</p>
                    @endif

                    <div class="text-sm text-gray-700">
                        <p class="flex justify-between"><span>Subtotal:</span><span>{!! WC()->cart->get_cart_subtotal() !!}</span></p>
                        @if( ! is_cart() )
                            <!-- Mostrar envío solo si NO estamos en el carrito -->
                            <p class="flex justify-between"><span>Envío:</span><span>{!! WC()->cart->get_cart_shipping_total() !!}</span></p>
                        @endif
                        @php
                            $raw_total   = (float) WC()->cart->get_total('edit'); // número crudo
                            $ship_amount = (float) WC()->cart->get_shipping_total() + (float) WC()->cart->get_shipping_tax();
                            $cart_total_without_shipping = max(0, $raw_total - $ship_amount);
                        @endphp
                        <p class="flex justify-between font-bold border-t pt-2">
                            <span>Total:</span>
                            <span>{!! wc_price($cart_total_without_shipping) !!}</span>
                        </p>

                    </div>

                    <textarea class="w-full p-2 border border-gray-300 rounded text-sm" placeholder="Instrucciones de pedido"></textarea>

                    <p class="text-xs text-gray-500">Impuestos y <span class="text-yellow-500 font-medium underline">gastos de envío</span> calculados en la caja</p>

                    <button
                        type="submit"
                        name="update_cart"
                        value="1"
                        class="block w-full text-center bg-blue-600 text-white py-3 rounded font-semibold hover:bg-blue-700 transition"
                    >
                        Actualizar carrito
                    </button>

                    <a href="https://blessrom.com/tienda/"
                    class="block text-center bg-[#FDC700] text-gray-900 py-3 rounded font-semibold hover:brightness-90 transition mt-2">
                    ← Seguir comprando
                    </a>

                    <a href="{{ wc_get_checkout_url() }}" class="block text-center bg-gray-800 text-white py-3 rounded font-semibold hover:bg-gray-700 transition">
                        Ir a caja
                    </a>
                </div>
            </div>

            @php do_action('woocommerce_cart_actions'); @endphp
        </form>
    @endif
</div>
