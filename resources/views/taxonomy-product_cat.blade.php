@extends('layouts.app')

@section('content')

  <div class="bg-red-100 text-red-700 text-center p-4 mb-4">
    ✅ Cargando taxonomy-product_cat.blade.php
  </div>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-center mb-6">
      Categoría: {{ single_term_title('', false) }}
    </h1>

    @php
      $term = get_queried_object();
      $query = new WP_Query([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'tax_query'      => [
          [
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $term->slug,
          ]
        ],
      ]);
    @endphp

    @if ($query->have_posts())
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($query->posts as $post)
          @php
            $product = wc_get_product($post->ID);
          @endphp
          @continue(!$product instanceof \WC_Product)

          <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-4 flex flex-col justify-between relative h-full">
            <a href="{{ get_permalink($product->get_id()) }}">
              <div class="w-full aspect-square overflow-hidden rounded mb-4">
                <img
                  src="{{ wp_get_attachment_image_url($product->get_image_id(), 'medium') }}"
                  alt="{{ $product->get_name() }}"
                  class="w-full h-full object-cover transition-transform duration-300 ease-in-out hover:scale-105"
                />
              </div>

              <h3 class="text-base font-semibold text-gray-800 mb-1 truncate">
                {{ $product->get_name() }}
              </h3>

              <p class="text-[#333] font-bold text-sm mb-4">
                {!! $product->get_price_html() !!}
              </p>
            </a>

            <a href="{{ get_permalink($product->get_id()) }}"
              class="inline-block bg-[#FFB816] text-white text-sm font-semibold text-center py-2 px-4 rounded hover:bg-yellow-500 transition">
              Seleccionar opciones
            </a>
          </div>
        @endforeach
      </div>
    @else
      <p class="text-center text-gray-500">No hay productos en esta categoría.</p>
    @endif
  </div>
@endsection
