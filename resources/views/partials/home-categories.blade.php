@php

  
  $categories = get_terms([
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'parent'     => 0,
    'number'     => 18,
  ]);


// Helper: imagen aleatoria desde productos de la categoría
if (! function_exists('br_get_random_product_image_from_category')) {
  function br_get_random_product_image_from_category($term_id) {
    $q = new WP_Query([
      'post_type'      => 'product',
      'posts_per_page' => 1,
      'orderby'        => 'rand', // aleatorio en cada carga
      'tax_query'      => [[
        'taxonomy'         => 'product_cat',
        'field'            => 'term_id',
        'terms'            => $term_id,
        'include_children' => true, // incluye subcategorías
      ]],
      'no_found_rows'  => true,
      'fields'         => 'ids',
    ]);

    if ($q->have_posts()) {
      $product_id = $q->posts[0];
      $img = get_the_post_thumbnail_url($product_id, 'medium');
      wp_reset_postdata();
      return $img ?: wc_placeholder_img_src();
    }
    return wc_placeholder_img_src();
  }
}
@endphp

<section class="text-center popular-products py-2 px-0 -mx-4 sm:-mx-6 lg:-mx-8">
  <div class="mx-auto max-w-none">
    <div class="bg-white rounded-none sm:rounded-lg shadow-md p-0 sm:p-6">

      {{-- Título centrado --}}
      <header class="bg-white mb-6 flex w-full flex-col items-center text-center">
        <h2 id="home-products-title" class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-900">
          Colección - Mujer
        </h2>
        <p class="mt-1 text-sm text-slate-600">Explora nuestras categorías</p>
        <span class="mt-2 h-0.5 w-16 bg-[#FFB816] mx-auto"></span>
      </header>

      {{-- Usa "category-swiper" para distinguirlo de los de productos --}}
      <div class="swiper category-swiper overflow-visible">
        <div class="swiper-wrapper">
          @foreach ($categories as $cat)
            @php
              $image    = br_get_random_product_image_from_category($cat->term_id);
              $cat_link = get_term_link($cat);
              $cat_slug = $cat->slug;
            @endphp

            @if (! is_wp_error($cat_link))
              <div class="swiper-slide">
                <a href="{{ esc_url($cat_link) }}"
                   class="group grid grid-cols-1 md:grid-cols-5 gap-4 items-center bg-white rounded-2xl shadow-md hover:shadow-lg transition duration-300 p-4 sm:p-6 text-left">

                  {{-- 60% Imagen (izquierda) --}}
                  <div class="md:col-span-3">
                    <div class="relative w-full aspect-square overflow-hidden rounded-xl">
                      <img src="{{ esc_url($image) }}" alt="{{ esc_attr($cat->name) }}"
                           class="absolute inset-0 w-full h-full object-contain transition-transform duration-300 group-hover:scale-105" />
                    </div>
                  </div>

                  {{-- 40% Texto (derecha) --}}
                  <div class="md:col-span-2 min-w-0">
                    <h3 class="text-xl sm:text-2xl font-semibold text-gray-800 font-serif">
                      {{ $cat->name }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 line-clamp-2">
                      {{ $cat->description ?: 'Descubre esta categoría' }}
                    </p>

                    {{-- CTA opcional dentro de la tarjeta --}}
                    <span class="mt-4 inline-flex items-center gap-2 rounded-full bg-[#FFB816] px-5 py-2 text-white font-semibold hover:bg-yellow-500 transition">
                      Ver más
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"><path fill="currentColor" d="m10 17l5-5l-5-5v10Z"/></svg>
                    </span>
                  </div>

                </a>
              </div>
            @endif
          @endforeach
        </div>

        {{-- Flechas (ya tienes el JS para category-swiper) --}}
        <div class="swiper-button-prev category-swiper-button-prev !hidden md:!flex text-blue-500 absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>
        <div class="swiper-button-next category-swiper-button-next !hidden md:!flex text-blue-500 absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>
      </div>

      

    </div>
  </div>
</section>
