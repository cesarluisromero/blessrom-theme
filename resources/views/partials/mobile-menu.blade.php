{{-- Menú lateral móvil --}}
<div x-show="open" x-cloak @click.away="open = false" class="fixed inset-0 z-50 bg-black bg-opacity-50">
  <div class="bg-white text-black w-64 h-full p-6 shadow-lg transform transition-transform duration-300 ease-in-out overflow-y-auto"
    x-transition:enter="transform transition ease-out duration-300"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transform transition ease-in duration-300"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full">

    {{-- Botón de cierre --}}
    <div class="flex justify-end mb-4">
        <button @click="open = false" class="text-gray-600 hover:text-red-500 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        </button>
    </div>

    <h3 class="font-semibold mb-2">Ofertas Especiales</h3>
        <a href="#" class="block py-1 hover:text-blue-600">Descuentos</a>
        <a href="#" class="block py-1 hover:text-blue-600">Nuevas Colecciones</a>

    <h3 class="font-semibold mt-4 mb-2">Categorías</h3>

     @include('partials.enlaces-menu-categoria')

  </div>
</div>
