<section class="py-8 text-center bg-white">
  <div class="container mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

      {{-- Estilo Urbano --}}
      <a href="{{ url('/categoria/estilo-urbano') }}" class="bg-[url('/wp-content/uploads/estilo-urbano.jpg')] bg-cover bg-center rounded-xl h-48 flex items-center justify-center text-white text-lg font-bold hover:opacity-90 transition">
        Estilo urbano
      </a>

      {{-- Días calurosos --}}
      <a href="{{ url('/categoria/dias-calurosos') }}" class="bg-[url('/wp-content/uploads/dias-calurosos.jpg')] bg-cover bg-center rounded-xl h-48 flex items-center justify-center text-white text-lg font-bold hover:opacity-90 transition">
        Para días calurosos
      </a>

      {{-- Lo más nuevo --}}
      <a href="{{ url('/categoria/novedades') }}" class="bg-[url('/wp-content/uploads/novedades.jpg')] bg-cover bg-center rounded-xl h-48 flex items-center justify-center text-white text-lg font-bold hover:opacity-90 transition">
        Lo más nuevo
      </a>

      {{-- Ofertas --}}
      <a href="{{ url('/categoria/ofertas') }}" class="bg-[url('/wp-content/uploads/ofertas.jpg')] bg-cover bg-center rounded-xl h-48 flex items-center justify-center text-white text-lg font-bold hover:opacity-90 transition">
        Ofertas del mes
      </a>

    </div>
  </div>
</section>
