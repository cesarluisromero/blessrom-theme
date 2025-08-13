@php
  /** @var WC_Product $product */
  $product    = $product ?? wc_get_product(get_the_ID());
  if (!$product) return;

  $permalink  = get_permalink($product->get_id());
  $title      = $product->get_name();
  $img_url    = get_the_post_thumbnail_url($product->get_id(), 'woocommerce_thumbnail') ?: wc_placeholder_img_src();
  $on_sale    = $product->is_on_sale();
  $in_stock   = $product->is_in_stock();
  $price_html = $product->get_price_html();
  $rating     = function_exists('wc_get_rating_html') ? wc_get_rating_html($product->get_average_rating()) : '';
  $is_variable= $product->is_type('variable');
@endphp

<article class="group flex flex-col h-full rounded-2xl bg-white shadow hover:shadow-lg transition p-3">
  <a href="{{ esc_url($permalink) }}" class="block relative rounded-xl overflow-hidden">
    {{-- Badges --}}
    <div class="absolute z-10 top-2 left-2 flex flex-col gap-2">
      @if($on_sale)
        <span class="text-[11px] uppercase font-semibold tracking-wide bg-rose-600 text-white px-2 py-1 rounded-full">Oferta</span>
      @endif
      @unless($in_stock)
        <span class="text-[11px] uppercase font-semibold tracking-wide bg-slate-800 text-white px-2 py-1 rounded-full">Sin stock</span>
      @endunless
    </div>

    {{-- Imagen (ratio) --}}
    <div class="relative w-full overflow-hidden rounded-xl">
      <div class="pt-[125%]"></div> {{-- ratio 4:5 aprox para moda --}}
      <img
        src="{{ esc_url($img_url) }}"
        alt="{{ esc_attr($title) }}"
        class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.04]"
        loading="lazy"
      >
    </div>
  </a>

  {{-- Info --}}
  <div class="mt-3 flex-1 flex flex-col">
    <a href="{{ esc_url($permalink) }}" class="block">
      <h3 class="text-sm md:text-[15px] font-semibold text-slate-800 line-clamp-2">{{ esc_html($title) }}</h3>
    </a>

    {{-- Rating opcional --}}
    @if(!empty($rating))
      <div class="mt-1 flex items-center gap-2">
        {!! $rating !!}
      </div>
    @endif

    {{-- Precio --}}
    @if(!empty($price_html))
      <div class="mt-1 text-base font-semibold text-slate-900">{!! $price_html !!}</div>
    @endif

    {{-- CTA --}}
    <div class="mt-3">
      @if(!$in_stock)
        <button class="w-full rounded-xl px-4 py-2.5 text-sm font-semibold bg-slate-200 text-slate-500 cursor-not-allowed">Agotado</button>
      @elseif($is_variable)
        <a href="{{ esc_url($permalink) }}" class="w-full inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold bg-slate-900 text-white hover:bg-slate-800">
          Ver opciones
        </a>
      @else
        {{-- Para simples usamos el botón nativo (respeta inventario/stock/backorders) --}}
        <form class="cart" action="{{ esc_url( $product->add_to_cart_url() ) }}" method="post" enctype="multipart/form-data">
          <input type="hidden" name="add-to-cart" value="{{ esc_attr( $product->get_id() ) }}">
          <button type="submit"
            class="w-full rounded-xl px-4 py-2.5 text-sm font-semibold bg-slate-900 text-white hover:bg-slate-800">
            Agregar al carrito
          </button>
        </form>
      @endif
    </div>
  </div>
</article>
