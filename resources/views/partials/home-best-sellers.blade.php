<section class="py-2 text-center">
  <div class="container mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
      @php
        $productos = wc_get_products([
          'limit' => 4,
          'orderby' => 'popularity',
          'order' => 'desc',
        ]);
      @endphp

      @foreach ($productos as $producto)
      <div class="bg-white rounded-lg shadow p-4 hover:shadow-md transition">
        <a href="{{ get_permalink($producto->get_id()) }}">
          <div class="aspect-[13/12] overflow-hidden rounded">
          <img
              src="{{ wp_get_attachment_image_url($producto->get_image_id(), 'medium') }}"
              alt="{{ $producto->get_name() }}"
              class="rounded-xl w-64 h-64 object-contain mb-4 transition-transform duration-300 hover:scale-105"
            />        
          </div>
          <h3 class="text-lg mb-2 font-serif">{{ $producto->get_name() }}</h3>
          <p class= "font-serif text-sm">{!! $producto->get_price_html() !!}</p>
          <span class="mt-2 inline-block bg-[#FFB816] text-white text-sm font-semibold py-2 px-4 rounded hover:bg-yellow-500 transition">
            Ver producto
          </span>
        </a>
      </div>

      @endforeach
    </div>
  </div>
</section>

