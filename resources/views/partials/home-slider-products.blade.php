<section class="text-center popular-products  py-2 px-4">
  <div class="max-w-screen-2xl mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
      <div class="swiper product-swiper">
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
      

        <!-- Botones -->
        
        <div class="swiper-button-prev product-swiper-button-prev !hidden md:!flex text-blue-500 absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>

        <div class="swiper-button-next product-swiper-button-next !hidden md:!flex text-blue-500 absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>

     

        <div class="swiper-pagination product-swiper-pagination block md:hidden mt-4"></div>



        <!-- Barra inferior -->
        <div class="swiper-scrollbar"></div>
      </div>
    </div>
  </div>
</section>




