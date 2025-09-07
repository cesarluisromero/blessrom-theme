
@php
$categories = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'number' => 20, // puedes ajustar esto
]);

function get_random_product_image_from_category($category_id) {
    $args = [
        'post_type' => 'product',
        'posts_per_page' => 1,
        'orderby' => 'rand',
        'tax_query' => [[
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $category_id,
        ]]
    ];
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        $query->the_post();
        $image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
        wp_reset_postdata();
        return $image;
    }
    return wc_placeholder_img_src(); // imagen por defecto si no hay productos
}
@endphp
<section class="text-center popular-products py-2 px-0 -mx-4 sm:-mx-6 lg:-mx-8">
  <div class="mx-auto max-w-none"> 
    <div class="bg-white rounded-none sm:rounded-lg shadow-md p-0 sm:p-6">
      {{-- Título centrado --}}
      <header class="bg-white mb-6 flex w-full flex-col items-center text-center">
        <h2 id="home-products-title" class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-900">
          Nuestras Categorías - Para ti
        </h2>
        <p class="mt-1 text-sm text-slate-600">Lo que encontrarás en nuestra tienda</p>
        <span class="mt-2 h-0.5 w-16 bg-[#FFB816] mx-auto"></span>
      </header>
      <div class="swiper category-swiper">
        <div class="swiper-wrapper">
           @foreach($categories as $cat)
    @php 
      $image    = get_random_product_image_from_category($cat->term_id);
      $cat_link = get_term_link($cat);
      $cat_slug = $cat->slug; // más fiable que basename()
    @endphp

    <div class="swiper-slide">
      <a href="{{ esc_url($cat_link . '?min_price=5&max_price=500&categorias%5B%5D=' . $cat_slug) }}"
         class="group grid grid-cols-1 md:grid-cols-5 gap-4 items-center bg-white rounded-2xl shadow-md hover:shadow-lg transition duration-300 p-4 sm:p-6 text-left">
        
        {{-- 60% Imagen --}}
        <div class="md:col-span-3">
          <div class="relative w-full aspect-square overflow-hidden rounded-xl">
            <img src="{{ esc_url($image) }}" alt="{{ esc_attr($cat->name) }}"
                 class="absolute inset-0 w-full h-full object-contain transition-transform duration-300 group-hover:scale-105" />
          </div>
        </div>

        {{-- 40% Texto --}}
        <div class="md:col-span-2 min-w-0">
          <h3 class="text-xl sm:text-2xl font-semibold text-gray-800 font-serif">
            {{ $cat->name }}
          </h3>
          <p class="mt-1 text-sm text-gray-500 line-clamp-2">
            {{ $cat->description ?: 'Categorías' }}
          </p>

          <span class="mt-4 inline-flex items-center gap-2 rounded-full bg-[#FFB816] px-5 py-2 text-white font-semibold hover:bg-yellow-500 transition">
            Ver más
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"><path fill="currentColor" d="m10 17l5-5l-5-5v10Z"/></svg>
          </span>
        </div>

      </a>
    </div>
  @endforeach
        </div> 

        {{-- Flechas de navegación --}}
        
        <div class="swiper-button-prev category-swiper-button-prev !hidden md:!flex text-blue-500 absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>

        <div class="swiper-button-next category-swiper-button-next !hidden md:!flex text-blue-500 absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>

        
      </div>
    </div>
  </div>
</section>

