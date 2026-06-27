<x-layouts.app>
    @push('styles')
    <style>
        .product-detail-bg {
            background-color: #040810; /* Very dark grayish blue/black to match Laurent theme */
            min-height: 100vh;
            color: #ffffff;
            font-family: var(--font-sans, 'Jost', sans-serif);
            padding-top: 130px; /* Offset for fixed header */
            padding-bottom: 80px;
        }
        .laurent-title {
            font-family: var(--font-serif, 'Playfair Display', serif);
            color: #0077bb; /* Golden/Primary color from Laurent theme */
            letter-spacing: 0.25em;
            text-transform: uppercase;
        }
        .add-cart-btn {
            border: 1px solid #0077bb;
            color: #0077bb;
            background: transparent;
            padding: 12px 30px;
            font-size: 11px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }
        .add-cart-btn:hover {
            background: #0077bb;
            color: #040810;
        }
        .qty-input {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            text-align: center;
            width: 60px;
            height: 44px;
        }
        .qty-input:focus {
            outline: none;
            border-color: #0077bb;
        }
        /* Hide number arrows */
        .qty-input::-webkit-outer-spin-button,
        .qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .qty-input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    @endpush

    <div class="product-detail-bg" x-data="productDetail()">
        
        <!-- Header / Breadcrumbs -->
        <div class="max-w-[1400px] mx-auto px-6 md:px-12 flex flex-col md:flex-row justify-between items-center mb-12 border-b border-white/5 pb-8">
            <h1 class="laurent-title text-sm md:text-base mb-4 md:mb-0 tracking-[0.3em]">SHOP</h1>
            <div class="text-[12px] md:text-[13px] tracking-widest flex items-center text-gray-400">
                <a href="{{ route('welcome') }}" class="hover:text-[#0077bb] transition-colors">Trang chủ</a> 
                <span class="mx-3 text-white/30">»</span> 
                <a href="{{ route('menu') }}" class="hover:text-[#0077bb] transition-colors">Thực đơn</a> 
                <span class="mx-3 text-white/30">»</span> 
                <span class="text-white">{{ $food->name }}</span>
            </div>
        </div>

        <!-- Main Product Area -->
        <div class="max-w-[1400px] mx-auto px-6 md:px-12 flex flex-col lg:flex-row gap-12 lg:gap-20">
            
            <!-- Left: Image -->
            <div class="w-full lg:w-1/2">
                @php
                    $imageUrl = '/images/default-food.jpg';
                    if (!empty($food->image)) {
                        if (str_starts_with($food->image, '/') || str_starts_with($food->image, 'http')) {
                            $imageUrl = $food->image;
                        } else {
                            $imageUrl = Storage::url($food->image);
                        }
                    }
                @endphp
                <img src="{{ $imageUrl }}" alt="{{ $food->name }}" class="w-full h-auto object-cover border border-white/5 shadow-2xl">
            </div>

            <!-- Right: Details -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center">
                <h2 class="laurent-title text-3xl md:text-4xl mb-4 leading-tight">{{ $food->name }}</h2>
                
                <!-- Rating -->
                <div class="flex items-center gap-2 mb-6">
                    <div class="text-[#0077bb] text-sm tracking-[0.2em]">
                        &#9733; &#9733; &#9733; &#9733; &#9734;
                    </div>
                    <span class="text-gray-400 text-xs tracking-wider">(2 đánh giá của khách hàng)</span>
                </div>
                
                <!-- Price -->
                <div class="text-[#0077bb] text-2xl font-serif mb-8 tracking-widest">
                    {{ number_format($food->price * 1000, 0, ',', '.') }} VNĐ
                </div>
                
                <!-- Description -->
                <div class="text-gray-400 text-[14px] md:text-[15px] leading-relaxed mb-10 text-justify font-light">
                    {!! nl2br(e($food->description ?? 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.')) !!}
                </div>
                
                <!-- Add to Cart -->
                <div class="flex items-center gap-4 mb-12">
                    <div class="flex items-center">
                        <button @click="if(quantity > 1) quantity--" class="w-10 h-[44px] bg-transparent border border-white/10 text-gray-400 hover:text-white hover:border-white/30 transition-colors flex items-center justify-center border-r-0">
                            -
                        </button>
                        <input type="number" x-model.number="quantity" min="1" class="qty-input">
                        <button @click="quantity++" class="w-10 h-[44px] bg-transparent border border-white/10 text-gray-400 hover:text-white hover:border-white/30 transition-colors flex items-center justify-center border-l-0">
                            +
                        </button>
                    </div>
                    <button @click="addToCart({{ $food->id }}, '{{ addslashes($food->name) }}', {{ $food->price }}, '{{ $imageUrl }}')" class="add-cart-btn flex items-center gap-2">
                        <span>ADD TO CART</span>
                    </button>
                </div>
                
                <!-- Success Message -->
                <div x-show="showSuccess" x-transition class="text-[#0077bb] text-sm tracking-wider mb-6" style="display: none;">
                    ✓ Đã thêm vào giỏ hàng thành công! (Vui lòng vào giỏ hàng hoặc về trang Đặt món tại bàn để tiếp tục)
                </div>
                
                <!-- Meta Info -->
                <div class="flex flex-col gap-4 text-[13px] tracking-[0.15em] text-gray-400 border-t border-white/5 pt-8">
                    <div>
                        <span class="text-[#0077bb] uppercase mr-2 font-serif">SKU:</span> {{ str_pad($food->id, 3, '0', STR_PAD_LEFT) }}
                    </div>
                    <div>
                        <span class="text-[#0077bb] uppercase mr-2 font-serif">CATEGORY:</span> {{ $food->category->name ?? 'Thực đơn' }}
                    </div>
                    <div>
                        <span class="text-[#0077bb] uppercase mr-2 font-serif">TAGS:</span> Nổi bật, Đặc sản
                    </div>
                </div>
                
            </div>
            
        </div>
        
    </div>

    @push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('productDetail', () => ({
                quantity: 1,
                showSuccess: false,
                
                addToCart(id, name, price, image) {
                    let cart = [];
                    const savedCart = localStorage.getItem('qr_order_cart');
                    if (savedCart) {
                        try {
                            cart = JSON.parse(savedCart);
                        } catch (e) {
                            cart = [];
                        }
                    }
                    
                    const existingIndex = cart.findIndex(item => item.id === id);
                    if (existingIndex !== -1) {
                        cart[existingIndex].quantity += this.quantity;
                    } else {
                        cart.push({ id, name, price, image, quantity: this.quantity });
                    }
                    
                    localStorage.setItem('qr_order_cart', JSON.stringify(cart));
                    
                    this.showSuccess = true;
                    setTimeout(() => {
                        window.location.href = "{{ route('order_at_table') }}";
                    }, 800);
                }
            }));
        });
    </script>
    @endpush
</x-layouts.app>
