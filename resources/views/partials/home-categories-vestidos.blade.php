@php
// Solo la categoría con slug 'vestido'
$categories = get_terms([
  'taxonomy'   => 'product_cat',
  'slug'       => ['vestido'],   // 👈 filtra por slug
  'hide_empty' => true,
  'number'     => 1,
]);

function get_random_product_image_from_category($category_id) {
  $q = new WP_Query([
    'post_type'      => 'product',
    'posts_per_page' => 1,
    'orderby'        => 'rand',
    'tax_query'      => [[
      'taxonomy' => 'product_cat',
      'field'    => 'term_id',
      'terms'    => $category_id,
    ]],
  ]);

  if ($q->have_posts()) {
    $q->the_post();
    $img = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: wc_placeholder_img_src();
    wp_reset_postdata();
    return $img;
  }

  return wc_placeholder_img_src();
}
@endphp

@if (!empty($categories) && !is_wp_error($categories))
<section class="py-2">
  <div class="container mx-auto px-4">
    <div class="swiper vestidos-swiper">
      <div class="swiper-wrapper">
        @foreach ($categories as $cat)
          @php
            $image    = get_random_product_image_from_category($cat->term_id);
            $cat_link = get_term_link($cat);
            // Usa el slug real de la taxonomía (más fiable que basename())
            $cat_slug = $cat->slug;
          @endphp

          @if (!is_wp_error($cat_link))
          <div class="swiper-slide">
            <a href="{{ esc_url($cat_link . '?min_price=5&max_price=500&categorias%5B%5D=' . $cat_slug) }}"
               class="bg-white rounded-2xl shadow-md hover:shadow-lg transition duration-300 p-10 flex flex-col items-center text-center">
              <img src="{{ esc_url($image) }}" alt="{{ esc_attr($cat->name) }}"
                   class="rounded-xl w-64 h-64 object-contain mb-4 transition-transform duration-300 hover:scale-105" />
              <h3 class="text-4xl font-semibold text-gray-700 mb-1 font-serif">{{ $cat->name }}</h3>
              <p class="text-sm text-gray-500 mb-3">{{ $cat->description ?: 'Categorías' }}</p>
              <span class="inline-block bg-[#FFB816] text-white text-lg font-semibold px-6 py-2 rounded-full hover:bg-yellow-500 transition">Ver más</span>
            </a>
          </div>
          @endif
        @endforeach
      </div>

      {{-- Controles --}}
      <div class="swiper-button-prev vestidos-swiper-button-prev !hidden md:!flex text-blue-500 absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>
      <div class="swiper-button-next vestidos-swiper-button-next !hidden md:!flex text-blue-500 absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>
      <div class="swiper-pagination vestidos-swiper-pagination block md:hidden mt-4"></div>
    </div>
  </div>
</section>
@endif
