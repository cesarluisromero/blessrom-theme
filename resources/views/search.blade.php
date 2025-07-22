@extends('layouts.app')

@section('content')
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-center mb-6">
      Resultados para: "{{ get_search_query() }}"
    </h1>

    @php
      $query = new WP_Query([
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        's' => get_search_query(),
      ]);
    @endphp

    @if ($query->have_posts())
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($query->posts as $post)
          @php $product = wc_get_product($post->ID); @endphp
          @continue(!$product instanceof \WC_Product)

          <div class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition">
            <a href="{{ get_permalink($product->get_id()) }}">
              <img src="{{ wp_get_attachment_image_url($product->get_image_id(), 'medium') }}"
                   alt="{{ $product->get_name() }}"
                   class="w-full h-48 object-cover mb-4 rounded" />
              <h3 class="text-base font-semibold">{{ $product->get_name() }}</h3>
              <p class="text-sm font-bold">{!! $product->get_price_html() !!}</p>
            </a>
          </div>
        @endforeach
      </div>
    @else
      <p class="text-center text-gray-500">No se encontraron productos relacionados.</p>
    @endif
  </div>
@endsection
