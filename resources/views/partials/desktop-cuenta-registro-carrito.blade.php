
      {{-- Íconos --}}
      <div class="flex items-center space-x-3">
        {{--<a href="#" class="hover:underline">Registrate</a>--}}
        <a href="https://blessrom.com/mi-cuenta/" class="hover:underline">Mi cuenta</a>
        <a href="https://blessrom.com/carrito/" class="relative hover:underline widget_shopping_cart_content bg-white text-white p-2 rounded-full">
          🛒
          <span id="cart-count" class="absolute -top-2 -right-2 bg-[#FFB816] text-white text-xs font-bold rounded-full px-1.5">
            {{ WC()->cart->get_cart_contents_count() }}
          </span>
        </a>
      </div>

      