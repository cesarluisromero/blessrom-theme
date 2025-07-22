<div x-data="{ showMenu: false }" class="relative">
    @include('partials.desktop-boton-menu')

    <div x-show="showMenu" x-cloak @click.away="showMenu = false"
         class="absolute bg-white text-black rounded shadow-lg mt-2 w-64 p-4 z-50 max-h-[80vh] overflow-y-auto">

        @include('partials.enlaces-menu-categoria')

    </div>
</div>
