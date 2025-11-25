
@php
$categories = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'number' => 18, // puedes ajustar esto
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
            Categorías Destacadas
          </h2>
          <p class="mt-1 text-sm text-slate-600">Lo mejor para ti aquí</p>
          <span class="mt-2 h-0.5 w-16 bg-[#FFB816] mx-auto"></span>
        </header>
    <div class="swiper category-swiper">
      <div class="swiper-wrapper">
        @foreach($categories as $cat)          
          @php 
            $image = get_random_product_image_from_category($cat->term_id);
            $cat_link = get_term_link($cat);  
            $cat_slug = basename(untrailingslashit($cat_link));          
          @endphp
          <div class="swiper-slide">
            <a href="{{ $cat_link . '?min_price=5&max_price=500&categorias%5B%5D=' . $cat_slug }}"
              class="group bg-white rounded-2xl shadow-md hover:shadow-lg transition p-3 sm:p-4 md:p-6 lg:p-10
                      flex flex-row md:flex-col items-center md:items-center gap-3 md:gap-4
                      text-left md:text-center">

              {{-- Imagen: 70% en móvil --}}
              <div class="flex-[0_0_60%] md:flex-none md:w-full">
                <img
                  src="{{ $image }}"
                  alt="{{ $cat->name }}"
                  class="w-full aspect-square object-contain rounded-xl
                        transition-transform duration-300 group-hover:scale-105
                        md:w-64 md:h-64 md:mx-auto" />
              </div>

              {{-- Texto + botón: 30% en móvil --}}
              <div class="flex-[0_0_40%] md:flex-none md:w-full min-w-0">
                <h3 class="text-base sm:text-lg md:text-xxl font-semibold text-gray-700 mb-1 font-serif truncate">
                  {{ $cat->name }}
                </h3>

                <p class="text-xs sm:text-sm text-gray-500 mb-2 md:mb-3 line-clamp-2">
                  {{ $cat->description ?: 'Categorías' }}
                </p>

                <span class="inline-block bg-[#FFB816] text-white text-sm sm:text-base font-semibold
                            px-3 py-1.5 sm:px-4 sm:py-2 rounded-full
                            group-hover:bg-yellow-500 transition">
                  Ver más
                </span>
              </div>
            </a>
          </div>
        @endforeach
      </div> 
      {{-- Flechas de navegación --}}
      <div class="swiper-button-prev category-swiper-button-prev !hidden md:!flex text-white absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-transparent rounded-full"></div>
      <div class="swiper-button-next category-swiper-button-next !hidden md:!flex text-white absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-transparent rounded-full"></div>
    </div>
  </div>
  </div>
</section>
