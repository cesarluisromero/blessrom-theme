@php $loginUrl = wc_get_page_permalink('myaccount') . '?redirect_to=' . urlencode(wc_get_checkout_url()); @endphp
<div class="mb-4 rounded-xl bg-blue-50 border border-blue-200 p-4 text-sm text-blue-900">
  <strong>¿Ya eres cliente?</strong>
  <a class="underline font-medium" href="{{ esc_url($loginUrl) }}">Haz clic aquí para acceder</a>.
</div>
