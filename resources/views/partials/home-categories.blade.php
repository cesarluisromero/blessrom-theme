
@php
$categories = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'number' => 32, // puedes ajustar esto
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
              <a href="{{ $cat_link . '?min_price=5&max_price=500&categorias%5B%5D=' . $cat_slug }}" class="bg-white rounded-2xl shadow-md hover:shadow-lg transition duration-300 p-10 flex flex-col items-center text-center">
                <img src="{{ $image }}" alt="{{ $cat->name }}" class="rounded-xl w-64 h-64 object-contain mb-4 transition-transform duration-300 hover:scale-105" />
                <h3 class="text-4xl font-semibold text-gray-700 mb-1 font-serif">{{ $cat->name }}</h3>
                <p class="text-sm text-gray-500 mb-3">{{ $cat->description ?: 'Categorías' }}</p>
                <span class="inline-block bg-[#FFB816] text-white text-lg font-semibold px-6 py-2 rounded-full hover:bg-yellow-500 transition">Ver más</span>
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

