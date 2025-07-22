<button @click="open = !open" class="text-sm focus:outline-none">
    <svg :class="{ 'rotate-90': open }" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
</button>