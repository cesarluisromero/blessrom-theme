@php
  $menu_items = wc_get_account_menu_items();
  $current = wc_get_account_endpoint();
@endphp

<ul class="space-y-2">
  @foreach ($menu_items as $endpoint => $label)
    <li>
      <a 
        href="{{ esc_url(wc_get_account_endpoint_url($endpoint)) }}" 
        class="block px-4 py-2 rounded-md text-sm font-medium 
               {{ $current === $endpoint ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-blue-100' }}">
        {{ $label }}
      </a>
    </li>
  @endforeach
</ul>
