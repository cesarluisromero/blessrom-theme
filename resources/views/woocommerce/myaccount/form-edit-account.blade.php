@extends('layouts.app')

@section('content')
  
@php
    do_action('woocommerce_before_edit_account_form');
@endphp

<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-md">
    <form method="post"
          class="woocommerce-EditAccountForm edit-account space-y-6"
          action=""
          {!! do_action('woocommerce_edit_account_form_tag') !!}>

        @php do_action('woocommerce_edit_account_form_start'); @endphp

        <h2 class="text-2xl font-bold text-gray-800 mb-6">Editar mi cuenta</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                    <label for="account_first_name" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('First name', 'woocommerce') }} <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <input type="text" name="account_first_name" id="account_first_name" autocomplete="given-name"
                        value="{{ old('account_first_name', $user->first_name) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary">
                </p>
            </div>
            <div>
                <label for="account_last_name" class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                <input type="text" name="account_last_name" id="account_last_name" autocomplete="family-name"
                       value="{{ old('account_last_name', $user->last_name) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary">
            </div>
        </div>

        <div>
            <label for="account_display_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre público</label>
            <input type="text" name="account_display_name" id="account_display_name"
                   value="{{ old('account_display_name', $user->display_name) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary">
            <p class="text-xs text-gray-500 mt-1">Este nombre se mostrará en tu cuenta y en reseñas.</p>
        </div>

        <div>
            <label for="account_email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
            <input type="email" name="account_email" id="account_email" autocomplete="email"
                   value="{{ old('account_email', $user->user_email) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary">
        </div>

        @php do_action('woocommerce_edit_account_form_fields'); @endphp

        <div class="pt-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Cambiar contraseña</h3>

            <div class="space-y-4">
                <div>
                    <label for="password_current" class="block text-sm font-medium text-gray-700 mb-1">Contraseña actual</label>
                    <input type="password" name="password_current" id="password_current" autocomplete="off"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="password_1" class="block text-sm font-medium text-gray-700 mb-1">Nueva contraseña</label>
                    <input type="password" name="password_1" id="password_1" autocomplete="off"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="password_2" class="block text-sm font-medium text-gray-700 mb-1">Confirmar nueva contraseña</label>
                    <input type="password" name="password_2" id="password_2" autocomplete="off"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary">
                </div>
            </div>
        </div>

        @php do_action('woocommerce_edit_account_form'); @endphp

        <div class="pt-6">
            @php wp_nonce_field('save_account_details', 'save-account-details-nonce'); @endphp
            @php
                $buttonClass = 'woocommerce-Button button';
                $themeButtonClass = wc_wp_theme_get_element_class_name('button');
                if ($themeButtonClass) {
                    $buttonClass .= ' ' . esc_attr($themeButtonClass);
                }
            @endphp

            <button type="submit"
                class="{{ $buttonClass }}"
                name="save_account_details"
                value="{{ esc_attr__('Save changes', 'woocommerce') }}">
                {{ __('Save changes', 'woocommerce') }}
            </button>
            <input type="hidden" name="action" value="save_account_details">
        </div>

        @php do_action('woocommerce_edit_account_form_end'); @endphp
    </form>
</div>

@php do_action('woocommerce_after_edit_account_form'); @endphp


@endsection
