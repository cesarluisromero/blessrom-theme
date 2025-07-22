@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
  <h2 class="text-2xl font-bold mb-4 text-center">🔒 Recuperar contraseña</h2>
  <p class="text-gray-600 text-sm text-center mb-6">¿Olvidaste tu contraseña? Ingresa tu correo electrónico y te enviaremos un enlace para restablecerla.</p>

  <form method="post" class="woocommerce-ResetPassword reset-password">
    @csrf
    <p class="woocommerce-form-row form-row">
      <label for="user_login" class="block mb-2 text-sm font-medium">Correo electrónico</label>
      <input class="woocommerce-Input input-text w-full border rounded px-4 py-2" type="text" name="user_login" id="user_login" autocomplete="username" />
    </p>

    <div class="clear mb-4"></div>

    <p class="woocommerce-form-row form-row">
      <input type="hidden" name="wc_reset_password" value="true" />
      <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded hover:bg-blue-700 transition-all">Enviar enlace de recuperación</button>
    </p>

    {!! wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce', true, false) !!}
  </form>
</div>
@endsection
