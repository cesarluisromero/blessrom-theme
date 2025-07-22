@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-12 px-6 text-center">

        <h1 class="text-3xl font-bold text-green-600 mb-4">¡Gracias por tu compra!</h1>
        <p class="text-lg text-gray-700 mb-8">Tu pedido se ha procesado correctamente y estamos preparando el envío.</p>

        @if ($order)
            <div class="bg-white shadow-md rounded-lg p-6 text-left text-gray-800 mx-auto max-w-md">
                <h2 class="text-xl font-semibold mb-4">Resumen de tu pedido:</h2>

                <p class="mb-2"><strong>Número de pedido:</strong> {{ $order->get_order_number() }}</p>
                <p class="mb-2"><strong>Fecha:</strong> {{ $order->get_date_created()->format('d/m/Y H:i') }}</p>
                <p class="mb-2"><strong>Total:</strong> {!! $order->get_formatted_order_total() !!}</p>
                <p class="mb-2"><strong>Método de pago:</strong> {{ $order->get_payment_method_title() }}</p>
            </div>
        @endif

        <div class="mt-6 flex flex-col items-center gap-4">
            <a href="{{ home_url('/') }}"
               class="text-blue-600 hover:underline font-medium">
               Volver al inicio
            </a>

            <a href="https://wa.me/51949545854" target="_blank"
               class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-xl transition">
                ¿Tienes dudas? Escríbenos por WhatsApp
            </a>
        </div>
    </div>
@endsection
