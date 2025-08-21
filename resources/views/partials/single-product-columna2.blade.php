{{-- Columna 2: Información --}}
<div class="space-y-4 bg-white pl-2 md:pl-6 ml-4">
    <h2 class="text-2xl font-bold text-black-800">{{ $product->get_name() }}</h2>
    <div class="text-xl text-red-600 font-bold">{!! $product->get_price_html() !!}</div>

    {{-- Atributos personalizados: talla, color --}}
    <div class="mt-4">
        {!! woocommerce_template_single_add_to_cart() !!}
    </div>  
    

</div>