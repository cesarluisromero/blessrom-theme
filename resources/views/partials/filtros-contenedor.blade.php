{{-- Contenedor principal --}}
<div class="relative">
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    {{-- Sidebar de filtros (visible en móvil y escritorio) --}}
    <aside id="mobile-filters"
        class="fixed top-[80px] left-0 w-full h-[calc(100vh-80px)] bg-white z-80 lg:z-0 p-6 overflow-y-auto hidden 
        lg:static lg:block lg:col-span-1 lg:h-auto lg:overflow-visible lg:p-0 border-r border-gray-200">
      {{-- Encabezado del panel de filtros solo visible en móvil --}}
      <div class="flex justify-between items-center mb-4 lg:hidden">
        <h2 class="text-lg font-bold text-gray-800">Filtros</h2>
        <button onclick="document.getElementById('mobile-filters').classList.add('hidden'); document.getElementById('mobile-overlay').classList.add('hidden');" class="text-2xl text-gray-500 hover:text-gray-700">
          &times;
        </button>
      </div>
      @include('partials.filtros-productos')
    </aside>

    {{-- Contenido de productos --}}
    <div class="lg:col-span-3">
      @include('partials.filtros-contenedor-botones-movil')
      @include('partials.grid-productos')
    </div>
  </div>
</div>
