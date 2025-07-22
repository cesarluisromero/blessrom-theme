@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
  <h2 class="text-2xl font-bold mb-4 text-center">📝 Crear una cuenta</h2>
  <p class="text-gray-600 text-sm text-center mb-6">Regístrate para poder hacer tus compras y llevar el control de tus pedidos.</p>

  <form method="post" class="woocommerce-form woocommerce-form-register register space-y-4">
    @csrf
    @php do_action('woocommerce_register_form_start'); @endphp

    <div>
      <label for="reg_email" class="block text-sm font-medium mb-1">Correo electrónico</label>
      <input type="email" class="input-text w-full border rounded px-4 py-2" name="email" id="reg_email" autocomplete="email" value="{{ old('email') ?? '' }}" required>
    </div>

    @if ('yes' === get_option('woocommerce_registration_generate_password'))
      {{-- No se muestra el campo de contraseña si WooCommerce lo genera automáticamente --}}
    @else
      <div>
        <label for="reg_password" class="block text-sm font-medium mb-1">Contraseña</label>
        <input type="password" class="input-text w-full border rounded px-4 py-2" name="password" id="reg_password" autocomplete="new-password" required>
      </div>
    @endif

    @php do_action('woocommerce_register_form'); @endphp

    <div>
      <button type="submit" class="w-full bg-green-600 text-white py-2 px-6 rounded hover:bg-green-700 transition-all">
        Registrarse
      </button>
    </div>

    @php do_action('woocommerce_register_form_end'); @endphp
    @php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce') @endphp
    <input type="hidden" name="register" value="Registrarse" />
  </form>
</div>
@endsection
