{{-- Columna 3: Acciones y Descripción --}}
<div class="space-y-0 bg-white" >
    <p class="text-sm text-gray-700 leading-relaxed">
        {!! wpautop($product->get_short_description()) !!}
    </p>

    <form method="post" enctype="multipart/form-data">
        @php
        do_action('woocommerce_before_add_to_cart_button');
        do_action('woocommerce_before_add_to_cart_quantity');
        @endphp

       

        @php do_action('woocommerce_after_add_to_cart_button'); @endphp
    </form>

    
</div>