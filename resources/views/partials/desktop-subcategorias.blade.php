<div x-show="open" x-collapse class="pl-4 mt-1">
    @foreach ($children as $child)
        <a href="{{ url('/')}}/tienda/?categorias[]={{ $child->slug }}" class="block py-1 hover:text-blue-600">
            {{ $child->name }}
        </a>
    @endforeach
</div>
