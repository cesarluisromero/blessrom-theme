<button @click="showMenu = !showMenu" class="bg-[#165DFF] border border-white text-white px-4 py-2 rounded inline-flex items-center">
    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
    <span>Todas las categorías</span>
    <svg class="h-4 w-4 ml-2 transform transition-transform duration-200" :class="{ 'rotate-180': showMenu }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
</button>