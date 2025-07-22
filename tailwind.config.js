module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/scripts/**/*.js',
    './app/**/*.php',
    './woocommerce/**/*.php',
    './*.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

module.exports = {
  theme: {
    extend: {
      colors: {
        primary: '#4f46e5',        // azul indigo-600
        'primary-dark': '#4338ca' // azul indigo-700
      }
    }
  }
}
