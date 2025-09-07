{{-- Slider solo para escritorio (md en adelante) --}}
<section class="hidden md:block w-full text-center py-2 px-4">
  <div class="max-w-screen-2xl mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
      <div class="swiper bannervestidos-swiper">
        <!-- Contenedor de slides -->
      
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <img
              src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/Neutral-Modern-Fashion-Collection-Banner-scaled.png') }}"
              alt="Experimenta nuestra pasión por la moda en Tarapoto"
              class="w-full h-full object-cover"
              fetchpriority="high" decoding="async">
          </div>
          <div class="swiper-slide">
            <img
              src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/Neutral-Modern-Fashion-Collection-Banner-scaled.png') }}"
              alt="Colección de vestidos"
              class="w-full h-full object-cover"
              loading="lazy" decoding="async">
          </div>
        </div>
      

        <!-- Botones -->
        
        <div class="swiper-button-prev bannervestidos-swiper-button-prev !hidden md:!flex text-blue-500 absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>
        <div class="swiper-button-next bannervestidos-swiper-button-next !hidden md:!flex text-blue-500 absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>

      </div>
    </div>
  </div>
</section>

{{-- Slider solo para móvil (hasta sm) --}}
<section class="block md:hidden w-full">
  {!! do_shortcode('[smartslider3 slider="6"]') !!}
</section>


