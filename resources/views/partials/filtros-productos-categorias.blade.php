<div>
    <h3 class="font-semibold mb-1">Categorías</h3>
    <ul class="space-y-1 max-h-40 overflow-y-auto pr-1">
        @foreach (get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]) as $cat)
            <li>
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="categorias[]" value="{{ $cat->slug }}" class="accent-blue-500"
                {{ collect(request('categorias'))->contains($cat->slug) ? 'checked' : '' }}>
                <span>{{ $cat->name }}</span>
            </label>
            </li>
        @endforeach
    </ul>
</div>