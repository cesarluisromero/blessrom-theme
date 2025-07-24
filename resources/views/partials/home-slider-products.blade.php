<section class="text-center popular-products  py-2 px-4">
  <div class="max-w-screen-2xl mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
      <div class="swiper">
        <!-- Contenedor de slides -->
      
        <div class="swiper-wrapper">
          @foreach($products as $product)
            <div class="swiper-slide">
              <a href="{{ get_permalink($product->get_id()) }}" class="block">
              {!! $product->get_image('medium', ['class' => 'mx-auto']) !!}
              <p class="text-center mt-4 mb-6 text-sm">{{ $product->get_name() }}</p>
              </a>
            </div>
          @endforeach
        </div> 
      

        <!-- Botones -->
        
        <div class="swiper-pagination absolute bottom-1 inset-x-0 flex justify-center"></div>
        <!-- Barra inferior -->
        
      </div>
    </div>
  </div> 
</section>




