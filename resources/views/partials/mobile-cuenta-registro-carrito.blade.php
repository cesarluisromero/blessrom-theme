<div class="flex items-center gap-3">
      <a href="https://blessrom.com/mi-cuenta/" class="hover:underline text-sm">Mi cuenta</a>
      <a href="https://blessrom.com/carrito/" class="relative">
        🛒
        <span id="cart-count" class="absolute -top-2 -right-2 bg-[#FFB816] text-white text-xs font-bold rounded-full px-1.5">
          {{ WC()->cart->get_cart_contents_count() }}
        </span>
      </a>
</div>