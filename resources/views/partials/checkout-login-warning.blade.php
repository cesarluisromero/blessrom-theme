<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkoutForm = document.querySelector('form.checkout');
        const isUserLoggedIn = {{ is_user_logged_in() ? 'true' : 'false' }};

        if (!isUserLoggedIn && checkoutForm) {
            checkoutForm.addEventListener('submit', function (e) {
                e.preventDefault();

                if (!document.querySelector('#login-warning')) {
                    const warning = document.createElement('div');
                    warning.id = 'login-warning';
                    warning.className = 'p-4 my-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 rounded';
                    warning.innerHTML = `
                        <strong>⚠️ Debes iniciar sesión para completar el pedido con Izipay.</strong><br>
                        <a href="{{ wp_login_url( wc_get_checkout_url() ) }}"
                           class="mt-2 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Iniciar sesión
                        </a>
                    `;
                    checkoutForm.prepend(warning);
                }
            });
        }
    });
</script>
