<!doctype html>
<html class="m-0 p-0" @php(language_attributes())>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="facebook-domain-verification" content="f5qdlnwsoq0xcyzyaxtjshejjyiy5l" />
    <style>
      html, body {
        margin: 0 !important;
        padding: 0 !important;
      }
    </style>
    
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>     
     @vite([
      'resources/styles/app.css',
      'resources/scripts/app.js',
    ])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('styles')
    @php(wp_head()) 
  </head>

  <body class="m-0 p-0 bg-[#f0f0f0]" @php(body_class())>
    @php(wp_body_open())

    <div id="app" class="w-full">
      <a class="sr-only focus:not-sr-only" href="#main">
        {{ __('Skip to content', 'sage') }}
      </a>

      @include('sections.header')

      <main id="main" class="main">
        @yield('content')
      </main>

      @hasSection('sidebar')
        <aside class="sidebar">
          @yield('sidebar')
        </aside>
      @endif

      @include('sections.footer')
    </div>
    @php(do_action('get_footer'))    
    
    @stack('scripts') 
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        document.body.addEventListener('click', function (e) {
          const el = e.target.closest('a');
          if (el && el.href && el.classList.contains('dgwt-wcas-suggestion')) {
            window.location.href = el.href;
          }
        });
      });
    </script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const minInput = document.getElementById('min_price');
        const maxInput = document.getElementById('max_price');
        const slider = document.getElementById('price-slider');

        if (minInput && maxInput && slider) {
          const minPrice = parseFloat(minInput.value || 5);
          const maxPrice = parseFloat(maxInput.value || 500);
          const minLabel = document.getElementById('price-min-label');
          const maxLabel = document.getElementById('price-max-label');

          noUiSlider.create(slider, {
            start: [minPrice, maxPrice],
            connect: true,
            step: 1,
            range: {
              'min': 5,
              'max': 500
            },
            format: {
              to: value => Math.round(value),
              from: value => parseFloat(value)
            }
          });

          slider.noUiSlider.on('update', function (values, handle) {
              const [min, max] = values.map(v => Math.round(v));
              minInput.value = min;
              maxInput.value = max;
              if (minLabel) minLabel.innerText = `S/${min}`;
              if (maxLabel) maxLabel.innerText = `S/${max}`;
          });
        }  
      });
    </script>   
    
    @php(wp_footer())    
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  </body>
</html>
