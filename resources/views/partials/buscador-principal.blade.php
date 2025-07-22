<div x-data="{
    query: '',
    results: [],
    loading: false,
    show: false,
    search() {
        if (this.query.length < 2) {
            this.results = []
            this.show = false
            return
        }
        this.loading = true
        fetch(`<?php echo admin_url('admin-ajax.php'); ?>?action=custom_search&query=${this.query}`)
            .then(res => res.json())
            .then(data => {
                this.results = data
                this.show = true
                this.loading = false
            })
    },
    groupByType(items) {
        return items.reduce((groups, item) => {
            const type = item.type || 'Otros'
            if (!groups[type]) groups[type] = []
            groups[type].push(item)
            return groups
        }, {})
    }
}" @click.away="show = false" class="relative w-full max-w-3xl ml-2 sm:ml-10 lg:ml-[120px] z-50">

  <!-- Barra de búsqueda estilo Amazon/FiboSearch -->
  <div class="flex w-full bg-white rounded-md overflow-hidden shadow-sm focus-within:ring-2 focus-within:ring-yellow-400">
    <input
  type="text"
  placeholder="Buscar en blessrom.com"
  class="flex-grow px-4 py-2 text-sm text-gray-700 placeholder-gray-400 focus:outline-none"
  x-model="query"
  @input.debounce.300ms="search"
  @keydown.enter.prevent="window.location.href = `${window.location.origin}/blessrom/?s=${encodeURIComponent(query)}`"
/>
    <button class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 flex items-center justify-center transition" @click.prevent>
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
      </svg>
    </button>
  </div>

  <!-- Resultados desplegables con separadores -->
  <div x-show="show" x-cloak class="absolute top-full left-0 w-full bg-white border border-gray-200 rounded-b-md mt-1 shadow-lg z-50 max-h-[400px] overflow-y-auto">
    <template x-if="loading">
      <div class="p-4 text-gray-500 text-sm">Buscando...</div>
    </template>

    <template x-if="!loading && results.length === 0">
      <div class="p-4 text-gray-500 text-sm">Sin resultados.</div>
    </template>

    <template x-if="!loading && results.length > 0">
      <div>
        <template x-for="(group, type) in groupByType(results)" :key="type">
          <div>
            <!-- Encabezado del tipo (ej: Categoría, Producto) -->
            <div class="px-4 py-2 text-xs font-semibold text-gray-500 bg-gray-100 uppercase">
              <span x-text="type"></span>
            </div>

            <!-- Resultados de busqueda con imagen en miniatura-->
            <template x-for="item in group" :key="item.id">
              <a :href="item.url" class="flex items-center px-4 py-2 hover:bg-gray-100 transition space-x-4">
                <img :src="item.image" alt="" class="w-10 h-10 object-cover rounded" />
                <div>
                  <div class="font-semibold text-gray-800" x-text="item.title"></div>
                </div>
              </a>
            </template>
          </div>
        </template>
      </div>
    </template>
  </div>
</div>
