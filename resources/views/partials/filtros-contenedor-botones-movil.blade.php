@php
  $filtros_activados = 0;

  if (!empty($_GET['categorias'])) {
    $filtros_activados += count((array) $_GET['categorias']);
  }

  if (!empty($_GET['talla'])) {
    $filtros_activados += count((array) $_GET['talla']);
  }

  if (!empty($_GET['marcas'])) {
    $filtros_activados += count((array) $_GET['marcas']);
  }

  if (!empty($_GET['min_price']) || !empty($_GET['max_price'])) {
    $filtros_activados++;
  }
@endphp

@if ($filtros_activados > 0)
    <div class="lg:hidden px-4 pt-4 pb-2 bg-white flex justify-between items-center space-x-2">
        {{-- Botón Filtros --}}
        <button
            onclick="document.getElementById('mobile-filters').classList.remove('hidden'); document.getElementById('mobile-overlay').classList.remove('hidden');"
            class="flex items-center gap-2 text-sm font-semibold text-gray-800 border border-gray-300 bg-white px-4 py-2 rounded-lg shadow hover:bg-gray-100 transition"
            >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 019 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
            </svg>
            Filtros ({{ $filtros_activados }})
        </button>

        {{-- Botón Limpiar --}}
        <a href="{{ url()->current() }}"
        class="flex items-center gap-2 text-sm text-gray-600 border border-gray-200 px-3 py-2 rounded-lg hover:bg-gray-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Limpiar filtros
        </a>
    </div>
@else
    {{-- Solo botón Filtros sin contador --}}
    <div class="lg:hidden px-4 pt-4 pb-2 bg-white flex justify-end">
        <button
            onclick="document.getElementById('mobile-filters').classList.remove('hidden'); document.getElementById('mobile-overlay').classList.remove('hidden');"
            class="flex items-center gap-2 text-sm font-semibold text-gray-800 border border-gray-300 bg-white px-4 py-2 rounded-lg shadow hover:bg-gray-100 transition"
            >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 019 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
            </svg>
            Filtros
        </button>
    </div>
@endif