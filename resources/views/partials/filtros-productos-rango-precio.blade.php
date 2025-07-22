@php
  $min = isset($_GET['min_price']) ? intval($_GET['min_price']) : 5;
  $max = isset($_GET['max_price']) ? intval($_GET['max_price']) : 500;
@endphp
 
 {{-- Rango de Precio con slider --}}
<div>
    <h3 class="font-semibold mb-1">Precio</h3>
    <div class="text-sm text-gray-600 mb-1">
        <span id="price-min-label">${{ $min }}</span> –
        <span id="price-max-label">${{ $max }}</span>
    </div>
    <div id="price-slider" class="mb-2 h-[8px]"></div>
    <input type="hidden" name="min_price" id="min_price" value="{{ $min }}">
    <input type="hidden" name="max_price" id="max_price" value="{{ $max }}">
</div>

  @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nouislider@15.6.1/dist/nouislider.min.css">
  @endpush

  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/nouislider@15.6.1/dist/nouislider.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const minInput = document.getElementById('min_price');
        const maxInput = document.getElementById('max_price');
        const minLabel = document.getElementById('price-min-label');
        const maxLabel = document.getElementById('price-max-label');
        const slider = document.getElementById('price-slider');

        if (!slider || !minInput || !maxInput || !minLabel || !maxLabel) return;

        const min = parseInt(minInput.value) || 5;
        const max = parseInt(maxInput.value) || 500;

        noUiSlider.create(slider, {
          start: [min, max],
          connect: true,
          step: 1,
          range: {
            min: 5,
            max: 500
          },
          format: {
            to: value => Math.round(value),
            from: value => parseFloat(value)
          }
        });

        slider.noUiSlider.on('update', function (values, handle) {
          const [minVal, maxVal] = values.map(v => Math.round(v));
          minInput.value = minVal;
          maxInput.value = maxVal;
          minLabel.innerText = `$${minVal}`;
          maxLabel.innerText = `$${maxVal}`;
        });
      });
    </script>
  @endpush