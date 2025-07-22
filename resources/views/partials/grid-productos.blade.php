{{-- Contenedor de productos --}}
<div class="lg:col-span-3">

  {{-- Mostrar la categoría actual si está en la URL (solo con filtro personalizado) --}}
  @if (!empty($_GET['categoria']))
    @php
      $term = get_term_by('slug', $_GET['categoria'], 'product_cat');
    @endphp

    @if ($term)
      <p class="text-center text-gray-500 mb-6">
        Mostrando productos de la categoría: <strong>{{ $term->name }}</strong>
      </p>
    @endif
  @endif

  @php
    $use_custom_query = $use_custom_query ?? true;
  @endphp

  @if ($use_custom_query)
    @php
      $args = wc()->query->get_catalog_ordering_args();

      $args['post_type'] = 'product';
      $args['post_status'] = 'publish';
      $args['posts_per_page'] = -1;

      $tax_query = [];

      if (!empty($_GET['categorias'])) {
        $slugs = array_map('sanitize_text_field', (array) $_GET['categorias']);
        $tax_query[] = [
          'taxonomy' => 'product_cat',
          'field'    => 'slug',
          'terms'    => $slugs,
          'operator' => 'IN',
        ];
      }

      if (!empty($_GET['talla'])) {
        $slugs = array_map('sanitize_text_field', (array) $_GET['talla']);
        $tax_query[] = [
          'taxonomy' => 'pa_talla',
          'field'    => 'slug',
          'terms'    => $slugs,
          'operator' => 'IN',
        ];
      }

      if (!empty($_GET['marcas'])) {
        $args['tax_query'][] = [
          'taxonomy' => 'product_brand',
          'field'    => 'slug',
          'terms'    => array_map('sanitize_text_field', (array) $_GET['marcas']),
          'operator' => 'IN',
        ];
      }

      if (!empty($tax_query)) {
        $tax_query['relation'] = 'AND';
        $args['tax_query'] = $tax_query;
      }

      if (!empty($_GET['min_price']) || !empty($_GET['max_price'])) {
        $args['meta_query'][] = [
          'key'     => '_price',
          'value'   => [
            isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0,
            isset($_GET['max_price']) ? floatval($_GET['max_price']) : 999999,
          ],
          'compare' => 'BETWEEN',
          'type'    => 'NUMERIC',
        ];
      }

      $query = new WP_Query($args);
    @endphp

    @if ($query->have_posts())
      <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-x-1 gap-y-1 sm:gap-1">
        @foreach ($query->posts as $post)
          @php $product = wc_get_product($post->ID); @endphp
          @continue(!$product instanceof \WC_Product)

          {{-- PRODUCTO --}}
          @include('partials.producto-card', ['product' => $product])
        @endforeach
      </div>
    @else
      <p class="text-center text-gray-500">No hay productos disponibles en esta categoría.</p>
    @endif

  @else
    @if (have_posts())
      <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-x-1 gap-y-1 sm:gap-1">
        @while (have_posts()) @php the_post() @endphp
          @php $product = wc_get_product(get_the_ID()) @endphp
          @continue(!$product instanceof \WC_Product)

          {{-- PRODUCTO --}}
          @include('partials.producto-card', ['product' => $product])
        @endwhile
      </div>
    @else
      <p class="text-center text-gray-500">No hay productos disponibles en esta categoría.</p>
    @endif
  @endif

</div>
