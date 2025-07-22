{{-- Columna 3: Acciones y Descripción --}}
<div class="bg-white rounded-2xl shadow-md p-6 max-w-md space-y-4 border border-gray-100">
    <div class="flex items-center justify-center gap-2 text-yellow-500 text-2xl">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4v16m8-8H4"/>
        </svg>
        <h2 class="text-gray-800 font-semibold text-lg">¿Por qué te encantará?</h2>
    </div>

    <div class="space-y-3 text-gray-700 text-base leading-relaxed">
        {!! wpautop($product->get_short_description()) !!}
    </div>

    <div class="pt-4 border-t border-gray-200 text-center">
        <p class="text-sm text-gray-500 italic">¡Luce increíble y siéntete con total comodidad donde vayas!</p>
    </div>
</div>