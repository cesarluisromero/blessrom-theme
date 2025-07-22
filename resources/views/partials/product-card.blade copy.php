@php $product = wc_get_product(get_the_ID()); @endphp

<div class="bg-white rounded-lg shadow hover:shadow-lg transition p-4 flex flex-col justify-between relative h-full">
  {{-- Badge dinámico --}}
  @if ($product->is_on_sale())
    <span class="absolute top-4 left-4 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded z-10 shadow-md">
      Oferta
    </span>
  @elseif ($product->get_date_created() && $product->get_date_created()->is_after(now()->subDays(7)))
    <span class="absolute top-4 left-4 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded z-10 shadow-md">
      Nuevo
    </span>
  @endif

  <a href="{{ get_permalink($product->get_id()) }}">
    <div class="w-full aspect-square overflow-hidden rounded mb-4">
      <img
        src="{{ wp_get_attachment_image_url($product->get_image_id(), 'medium') }}"
        alt="{{ $product->get_name() }}"
        class="w-full h-full object-cover transition-transform duration-300 ease-in-out hover:scale-105"
      />
    </div>
    <h3 class="text-base font-semibold text-gray-800 mb-1 truncate">{{ $product->get_name() }}</h3>
    <p class="text-[#333] font-bold text-sm mb-4">{!! $product->get_price_html() !!}</p>
  </a>

  <a href="{{ get_permalink($product->get_id()) }}"
     class="inline-block bg-[#FFB816] text-white text-sm font-semibold text-center py-2 px-4 rounded hover:bg-yellow-500 transition">
    Seleccionar opciones
  </a>
</div>
