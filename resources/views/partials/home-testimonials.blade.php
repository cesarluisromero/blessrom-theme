<section class="py-2 text-center">
  <div class="container mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      @foreach ([
        ['mensaje' => '“Los productos son de excelente calidad, y el envío fue rapidísimo. ¡Muy recomendable!”', 'autor' => 'Ana M.'],
        ['mensaje' => '“Compré un short para mi esposo y le encantó. Volveré a comprar sin duda.”', 'autor' => 'Carlos R.'],
        ['mensaje' => '“Blessrom tiene la mejor atención al cliente que he visto. Resolvieron todas mis dudas.”', 'autor' => 'Rocío T.'],
      ] as $testimonio)
        <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
          <p class="italic text-gray-700 mb-4">“{{ $testimonio['mensaje'] }}”</p>
          <p class="font-semibold text-gray-900">— {{ $testimonio['autor'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

