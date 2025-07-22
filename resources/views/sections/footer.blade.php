<footer class="bg-gray-900 text-white py-10 mt-12">
  <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
    
    <!-- Columna 1: Sobre la tienda -->
    <div>
      <h3 class="text-lg font-semibold mb-4">Blessrom</h3>
      <p class="text-gray-400">
        Moda, estilo y calidad para toda la familia. Encuentra ropa, accesorios y más al mejor precio.
      </p>
    </div>

    <!-- Columna 2: Enlaces útiles -->
    <div>
      <h3 class="text-lg font-semibold mb-4">Enlaces</h3>
      <ul class="space-y-2">
        <li><a href="{{ url('/tienda') }}" class="hover:underline text-gray-300">Tienda</a></li>
        <li><a href="{{ url('/contacto') }}" class="hover:underline text-gray-300">Contacto</a></li>
        <li><a href="{{ url('/politica-devoluciones') }}" class="hover:underline text-gray-300">Política de devoluciones</a></li>
        <li><a href="{{ url('/terminos-condiciones') }}" class="hover:underline text-gray-300">Términos y condiciones</a></li>
      </ul>
    </div>

    <!-- Columna 3: Redes sociales -->
    <div>
      <h3 class="text-lg font-semibold mb-4">Síguenos</h3>
      <div class="flex space-x-4">
        <a href="#" class="text-gray-300 hover:text-white">Facebook</a>
        <a href="#" class="text-gray-300 hover:text-white">Instagram</a>
        <a href="#" class="text-gray-300 hover:text-white">WhatsApp</a>
      </div>
    </div>
  </div>

  <!-- Línea inferior -->
  <div class="mt-8 border-t border-gray-700 pt-4 text-center text-gray-500 text-xs">
    &copy; {{ date('Y') }} Blessrom. Todos los derechos reservados.
  </div>
</footer>

