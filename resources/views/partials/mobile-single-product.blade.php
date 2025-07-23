
<div class="text-center text-lg font-semibold text-gray-800 lg:hidden mb-6">
        {{ $product->get_name() }}
</div>
<div x-data="productGallery()" class="product-swiper swiper block lg:hidden mb-6">
    
    <div class="swiper-wrapper">
        @php $ids = array_merge([$main_image], $attachment_ids); @endphp
        @foreach ($ids as $id)
            <div class="swiper-slide">
                <img src="{{ wp_get_attachment_image_url($id, 'large') }}" class="w-full h-auto object-contain lg:hidden mb-6">
            </div>
        @endforeach
    </div>
    
    <div class="swiper-pagination absolute bottom-1 inset-x-0 flex justify-center"></div>   
</div>
<div class="px-4 py-4 space-y-3 lg:hidden mb-6">
    {{-- Precio --}}
    <div class="text-center text-xl font-bold text-blue-600">
        {!! $product->get_price_html() !!}
    </div>

    {{-- Atributos --}}
    <div class="text-sm text-center text-gray-700">
        {!! woocommerce_template_single_add_to_cart() !!}
    </div>
</div>
@push('scripts')
    <script>
       function productGallery() {
            return {
                swiper: null,

                init() {
                this.swiper = new Swiper(this.$el, {
                    slidesPerView: 1,
                    spaceBetween: 10,
                    pagination: {
                    el: this.$el.querySelector('.swiper-pagination'),
                    clickable: true
                    },
                    breakpoints: {
                    768: {
                        pagination: false,
                        swipe: false,
                        allowTouchMove: false
                    }
                    }
                });

                // 🔗 Enlazar al store Alpine
                Alpine.store('product').swiper = this.swiper;

                Alpine.store('product').slideToImage = (url) => {
                    const slides = this.swiper.slides;
                    for (let i = 0; i < slides.length; i++) {
                    const img = slides[i].querySelector('img');
                    if (img && img.src.split('?')[0] === url.split('?')[0]) {
                        this.swiper.slideToLoop(i); // porque loop está activado
                        break;
                    }
                    }
                };
                }
            }
            }
    </script>
@endpush