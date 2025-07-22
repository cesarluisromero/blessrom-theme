    <div>
        <h3 class="font-semibold mb-1">Marcas</h3>
        <ul class="space-y-1 max-h-40 overflow-y-auto pr-1">
            @foreach (get_terms(['taxonomy' => 'product_brand', 'hide_empty' => false]) as $brand)
                <li>
                    <label class="flex items-center space-x-2">
                    <input type="checkbox" name="marcas[]" value="{{ $brand->slug }}"
                        {{ collect(request('marcas'))->contains($brand->slug) ? 'checked' : '' }} class="accent-blue-500">
                    <span>{{ $brand->name }}</span>
                    </label>
                </li>
            @endforeach
        </ul>
    </div>