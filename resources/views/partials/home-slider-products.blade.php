@php
// Trae solo productos publicados en la categoría con slug "vestidos"
$products = wc_get_products([
  'status'    => 'publish',
  'limit'     => 18,          // ajusta
  'orderby'   => 'date',      // 'rand' si quieres aleatorio
  'order'     => 'DESC',
  'category'  => ['hombre-polos'],// slug(s) de product_cat
  'return'    => 'objects',
  'stock_status' => 'instock', 
]);
@endphp

<section class="hidden md:block text-center popular-products py-2 px-0 -mx-4 sm:-mx-6 lg:-mx-8"> 
  <div class="mx-auto max-w-none">     
    <div class="bg-white rounded-none sm:rounded-lg shadow-md p-0 sm:p-6">
      {{-- Título centrado --}}
      <header class="bg-white mb-6 flex w-full flex-col items-center text-center">
        <h2 id="home-products-title" class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-900">
          Colección Polos - Hombre
        </h2>
        <p class="mt-1 text-sm text-slate-600">Lo último en nuestra tienda</p>
        <span class="mt-2 h-0.5 w-16 bg-[#FFB816] mx-auto"></span>  
      </header>
      <div class="swiper product-swiper overflow-visible">
        <!-- Contenedor de slides -->
        <div class="swiper-wrapper">
          @foreach($products as $product)
            <div class="swiper-slide">
              <a href="{{ get_permalink($product->get_id()) }}" class="block">
              {!! $product->get_image('medium', ['class' => 'mx-auto']) !!}
              <p class="text-xxl text-center mt-4 mb-6 font-serif">{{ $product->get_name() }}</p>
              </a>
            </div>
          @endforeach
        </div> 
          <!-- button Ver todo -->
          <div class="mt-6 mb-10 flex justify-center">
          <a href="https://blessrom.com/tienda/?categorias[]=hombre-polos"
            class="inline-flex items-center gap-2 rounded-full bg-[#FFB816] px-6 py-3 text-white font-semibold shadow hover:bg-yellow-500">
            Ver Todo
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"><path fill="currentColor" d="m10 17l5-5l-5-5v10Z"/></svg>
          </a>
        </div>
        <!-- Botones -->
        <div class="swiper-button-prev product-swiper-button-prev !hidden md:!flex text-blue-500 absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>
        <div class="swiper-button-next product-swiper-button-next !hidden md:!flex text-blue-500 absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>
          
      </div>
    </div>
  </div>
</section>


<section class="block md:hidden w-full text-center popular-products py-2 px-0 -mx-4 sm:-mx-6 lg:-mx-8"> 
  <div class="mx-auto max-w-none">     
    <div class="bg-white rounded-none sm:rounded-lg shadow-md p-0 sm:p-6">
      {{-- Título centrado --}}
      <header class="bg-white mb-6 flex w-full flex-col items-center text-center">
        <h2 id="home-products-title" class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-900">
          Colección Polos - Hombre
        </h2>
        <p class="mt-1 text-sm text-slate-600">Lo último en nuestra tienda</p>
        <span class="mt-2 h-0.5 w-16 bg-[#FFB816] mx-auto"></span>  
      </header>
      <div class="swiper product-swiper overflow-visible">
        <!-- Contenedor de slides -->
        <div class="swiper-wrapper">
          @foreach ($products as $product)
    <div class="swiper-slide">
      <a href="{{ get_permalink($product->get_id()) }}"
         class="grid grid-cols-5 items-center gap-4 p-3 text-left">

        {{-- 60% imagen --}}
        <div class="col-span-3">
          <div class="relative w-full aspect-square">
            {!! $product->get_image('medium', [
              'class' => 'absolute inset-0 w-full h-full object-contain rounded-md',
              'alt'   => esc_attr($product->get_name()),
              'loading' => 'lazy'
            ]) !!}
          </div>
        </div>

        {{-- 40% texto --}}
        <div class="col-span-2 min-w-0">
          <p class="text-base sm:text-lg font-serif leading-snug line-clamp-2">
            {{ $product->get_name() }}
          </p>

          @php $price_html = $product->get_price_html(); @endphp
          @if (!empty($price_html))
            <span class="mt-1 block text-sm text-slate-600">{!! $price_html !!}</span>
          @endif
        </div>

      </a>
    </div>
  @endforeach
        </div> 
          <!-- button Ver todo -->
          <div class="mt-6 mb-10 flex justify-center">
          <a href="https://blessrom.com/tienda/?categorias[]=hombre-polos"
            class="inline-flex items-center gap-2 rounded-full bg-[#FFB816] px-6 py-3 text-white font-semibold shadow hover:bg-yellow-500">
            Ver Todo
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"><path fill="currentColor" d="m10 17l5-5l-5-5v10Z"/></svg>
          </a>
        </div>
        <!-- Botones -->
        <div class="swiper-button-prev product-swiper-button-prev !hidden md:!flex text-blue-500 absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>
        <div class="swiper-button-next product-swiper-button-next !hidden md:!flex text-blue-500 absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>
          
      </div>
    </div>
  </div>
</section>

