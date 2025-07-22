<aside class="lg:col-span-1 bg-white p-6 rounded-lg shadow space-y-6 border text-sm text-gray-800">
  <form method="get" class="space-y-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-2">Filtrar por</h2>
        @include('partials.filtros-productos-rango-precio')    
        @include('partials.filtros-productos-talla')
        @include('partials.filtros-productos-categorias')
        @include('partials.filtros-productos-marcas')
    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition text-sm font-semibold">
        Aplicar filtros
    </button>
  </form>

  
</aside>
