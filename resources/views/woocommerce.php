{{-- resources/views/woocommerce.php --}}
@extends('layouts.app')

@section('content')
  <div class="container mx-auto">
    <p class="text-red-600 font-bold">⚠️ Esta vista proviene de woocommerce.php</p>
    @php woocommerce_content(); @endphp
  </div>
@endsection
