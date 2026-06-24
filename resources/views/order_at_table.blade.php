@php
    $allFoodsList = [];
    $catList = [];
    foreach($categories as $category) {
        if ($category->food->count() > 0) {
            $catList[] = [
                'id' => $category->id,
                'name' => $category->name
            ];
            foreach($category->food as $food) {
                $imgSrc = str_starts_with($food->image, 'http') || str_starts_with($food->image, '/images/') 
                    ? $food->image 
                    : asset('storage/'.$food->image);
                    
                $allFoodsList[] = [
                    'id' => $food->id,
                    'name' => $food->name,
                    'price' => $food->price,
                    'image' => $imgSrc,
                    'category_id' => $category->id
                ];
            }
        }
    }
@endphp
<x-layouts.app>
    @push('styles')
    <style>
        /* Custom styles for product cards */
        .product-card {
            background-color: transparent;
            transition: all 0.3s ease;
        }
        .product-image-container {
            position: relative;
            overflow: hidden;
            background-color: #0d1114; /* Darker background for image */
            aspect-ratio: 1/1;
        }
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease, filter 0.3s ease;
        }
        .product-card:hover .product-image {
            transform: scale(1.05);
            filter: brightness(0.6);
        }
        .add-to-cart-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 10;
        }
        .product-card:hover .add-to-cart-overlay {
            opacity: 1;
        }
        .add-to-cart-btn {
            border: 1px solid #0077bb; /* Primary border */
            color: #0077bb;
            background: transparent;
            padding: 10px 20px;
            font-size: 11px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .add-to-cart-btn:hover {
            background: #0077bb;
            color: #ffffff;
        }
        .product-title {
            font-family: var(--font-serif, 'Playfair Display', serif);
            color: #ffffff; /* White title */
            font-size: 14px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-top: 20px;
            text-align: center;
        }
        .product-rating {
            color: #718096; /* Gray stars */
            font-size: 10px;
            text-align: center;
            margin-top: 5px;
            letter-spacing: 2px;
        }
        .product-price {
            color: #0077bb; /* Primary price */
            font-size: 14px;
            text-align: center;
            margin-top: 5px;
            font-weight: 500;
            font-family: var(--font-sans, 'Jost', sans-serif);
        }
        /* Cart Sidebar */
        #cart-sidebar {
            font-family: var(--font-sans, 'Jost', sans-serif);
        }
    </style>
    @endpush

    <div class="pt-[110px] pb-24 min-h-screen bg-[#0d1114]" x-data="orderCart()">
        <!-- Header -->
        <div class="px-6 md:px-[60px] py-8 border-b border-white/5 flex justify-center items-center">
            <h1 class="text-[22px] md:text-[28px] uppercase tracking-[0.2em] text-primary font-medium text-center" style="font-family: var(--font-serif, 'Playfair Display', serif);">
                ĐẶT MÓN TẠI BÀN <span class="font-sans" style="font-family: var(--font-sans, 'Jost', sans-serif);">{{ $tableId ? $tableId : '' }}</span>
            </h1>
            <template x-teleport="#cart-icon-container">
                <button @click="cartOpen = true" class="relative text-white hover:text-primary transition-colors focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span x-show="totalItems() > 0" x-text="totalItems()" class="absolute -top-2 -right-2 bg-[#ef2853] text-white text-[11px] font-sans font-medium w-5 h-5 flex items-center justify-center rounded-full"></span>
                </button>
            </template>
        </div>

        @if(session('success'))
        <div class="px-6 md:px-[60px] mt-8" x-init="clearCart()">
            <div class="bg-[#1a2e1e] border border-[#2f5e3b] text-[#8ce99a] px-6 py-4 rounded relative font-sans text-sm tracking-wide">
                {{ session('success') }}
            </div>
        </div>
        @endif

        <!-- Main Content Area: Grid + Sidebar -->
        <div class="px-6 md:px-[60px] py-12 flex flex-col md:flex-row gap-12">
            
            <!-- Menu Grid & Pagination (Left/Center) -->
            <div class="w-full md:w-3/4 lg:w-4/5">
                
                <!-- Top Bar: Results Count & Sort Dropdown -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 pb-4 border-b border-white/10 gap-4">
                    <span class="text-gray-400 text-sm tracking-wider">
                        Hiển thị <span x-text="filteredFoods.length"></span> kết quả
                    </span>
                    <select x-model="sortBy" class="bg-transparent text-gray-300 border border-white/20 px-4 py-2 text-sm focus:outline-none focus:border-primary">
                        <option value="latest" class="bg-[#040810]">Sắp xếp: Mới nhất</option>
                        <option value="popularity" class="bg-[#040810]">Sắp xếp: Phổ biến nhất</option>
                        <option value="rating" class="bg-[#040810]">Sắp xếp: Đánh giá trung bình</option>
                        <option value="price_low" class="bg-[#040810]">Sắp xếp: Giá từ thấp đến cao</option>
                        <option value="price_high" class="bg-[#040810]">Sắp xếp: Giá từ cao đến thấp</option>
                    </select>
                </div>

                <!-- Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-12">
                    <template x-for="item in paginatedFoods" :key="item.id">
                        <div class="product-card group">
                            <div class="product-image-container border border-white/5">
                                <img :src="item.image" :alt="item.name" class="product-image">
                                <div class="add-to-cart-overlay">
                                    <button @click="addToCart(item.id, item.name, item.price, item.image)" class="add-to-cart-btn">
                                        Thêm vào giỏ
                                    </button>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title" x-text="item.name"></h3>
                                <div class="product-rating">
                                    &#9734; &#9734; &#9734; &#9734; &#9734;
                                </div>
                                <div class="product-price" x-text="formatPrice(item.price)"></div>
                            </div>
                        </div>
                    </template>
                </div>
                
                <!-- Pagination -->
                <div class="mt-16 flex justify-center items-center gap-4" x-show="totalPages > 1" style="display: none;">
                    <button @click="prevPage()" 
                            :disabled="currentPage === 1"
                            class="w-10 h-10 border border-white/20 text-white flex items-center justify-center hover:border-primary hover:text-primary transition-colors disabled:opacity-30 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    
                    <div class="flex gap-2">
                        <template x-for="page in totalPages" :key="page">
                            <button @click="currentPage = page"
                                    :class="currentPage === page ? 'bg-primary text-white border-primary' : 'border-white/20 text-gray-400 hover:text-white hover:border-white/50'"
                                    class="w-10 h-10 border flex items-center justify-center text-sm transition-colors focus:outline-none"
                                    x-text="page">
                            </button>
                        </template>
                    </div>
                    
                    <button @click="nextPage()" 
                            :disabled="currentPage === totalPages"
                            class="w-10 h-10 border border-white/20 text-white flex items-center justify-center hover:border-primary hover:text-primary transition-colors disabled:opacity-30 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
                
            </div>

            <!-- Category & Filter Sidebar (Right) -->
            <div class="w-full md:w-1/4 lg:w-1/5 flex flex-col gap-12">
                
                <!-- Price Filter -->
                <div>
                    <h3 class="text-white text-sm font-serif tracking-[0.2em] uppercase mb-6 border-b border-white/10 pb-4">LỌC THEO GIÁ</h3>
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-2">
                            <input type="number" x-model.number="filterMinPrice" class="w-full bg-transparent border border-white/20 text-white px-2 py-2 text-sm focus:outline-none focus:border-primary" placeholder="Min" min="0">
                            <span class="text-gray-500">-</span>
                            <input type="number" x-model.number="filterMaxPrice" class="w-full bg-transparent border border-white/20 text-white px-2 py-2 text-sm focus:outline-none focus:border-primary" placeholder="Max" min="0">
                        </div>
                        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mt-2 gap-4">
                            <button @click="minPrice = filterMinPrice; maxPrice = filterMaxPrice; currentPage = 1;" class="border border-white/20 text-white text-xs tracking-widest px-6 py-2 hover:bg-white hover:text-black transition-colors uppercase focus:outline-none">
                                Lọc
                            </button>
                            <div class="text-gray-400 text-xs mt-1 xl:mt-0">
                                Giá: <span x-text="new Intl.NumberFormat('vi-VN').format(minPrice) + ' VNĐ'"></span> &ndash; <span x-text="new Intl.NumberFormat('vi-VN').format(maxPrice) + ' VNĐ'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="flex flex-col gap-4">
                    <h3 class="text-white text-sm font-serif tracking-[0.2em] uppercase mb-2 border-b border-white/10 pb-4">DANH MỤC</h3>
                    
                    <button @click="setCategory('all')" 
                            :class="activeCategory === 'all' ? 'text-primary' : 'text-gray-400 hover:text-white'"
                            class="text-left text-sm transition-all duration-300 focus:outline-none">
                        Tất cả món ăn
                    </button>
                    
                    <template x-for="cat in categories" :key="cat.id">
                        <button @click="setCategory(cat.id)" 
                                :class="activeCategory === cat.id ? 'text-primary' : 'text-gray-400 hover:text-white'"
                                class="text-left text-sm transition-all duration-300 focus:outline-none"
                                x-text="cat.name">
                        </button>
                    </template>
                </div>
                
            </div>

        </div>

        <!-- Cart Sidebar Overlay -->
        <div x-show="cartOpen" class="fixed inset-0 bg-black/60 z-[120] backdrop-blur-sm transition-opacity" x-transition.opacity @click="cartOpen = false" style="display: none;"></div>
        
        <!-- Cart Sidebar -->
        <div x-show="cartOpen" id="cart-sidebar" class="fixed top-0 right-0 h-full w-[90vw] sm:w-[400px] bg-[#040810] border-l border-white/10 z-[130] flex flex-col shadow-2xl transform transition-transform duration-300" x-transition:enter="translate-x-full" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="translate-x-full" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" style="display: none;">
            
            <div class="p-6 border-b border-white/10 flex justify-between items-center">
                <h3 class="text-primary text-[13px] tracking-[0.2em] uppercase font-semibold">Giỏ hàng của bạn</h3>
                <button @click="cartOpen = false" class="text-gray-400 hover:text-white focus:outline-none">
                    <svg class="w-6 h-6 font-thin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                <template x-if="items.length === 0">
                    <div class="text-center text-gray-500 mt-10 text-sm tracking-wider">
                        Giỏ hàng trống
                    </div>
                </template>

                <div class="flex flex-col gap-6">
                    <template x-for="(item, index) in items" :key="item.id">
                        <div class="flex gap-4 items-center">
                            <div class="w-16 h-16 bg-[#0d1114] border border-white/5 flex-shrink-0 relative overflow-hidden">
                                <img :src="item.image || 'https://via.placeholder.com/100x100/1a202c/0077bb?text=Food'" 
                                     onerror="this.onerror=null; this.src='https://via.placeholder.com/100x100/1a202c/0077bb?text=Food';" 
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <h4 class="text-white text-[13px] font-serif tracking-wider" x-text="item.name"></h4>
                                <div class="text-primary text-[12px] mt-1" x-text="formatPrice(item.price)"></div>
                                
                                <div class="flex items-center gap-3 mt-2">
                                    <button @click="updateQuantity(index, -1)" class="w-6 h-6 border border-white/20 text-white flex items-center justify-center hover:border-primary hover:text-primary transition-colors">-</button>
                                    <span class="text-white text-[12px]" x-text="item.quantity"></span>
                                    <button @click="updateQuantity(index, 1)" class="w-6 h-6 border border-white/20 text-white flex items-center justify-center hover:border-primary hover:text-primary transition-colors">+</button>
                                </div>
                            </div>
                            <button @click="removeItem(index)" class="text-gray-500 hover:text-red-500 p-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="p-6 border-t border-white/10 bg-[#040810]">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-white text-[12px] tracking-[0.1em] uppercase">Tổng cộng:</span>
                    <span class="text-primary font-serif text-lg" x-text="formatPrice(cartTotal())"></span>
                </div>
                
                <form action="{{ route('order.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="table_id" value="{{ $tableId }}">
                    <input type="hidden" name="items" :value="JSON.stringify(items)">
                    <button type="submit" :disabled="items.length === 0" class="w-full bg-primary text-white py-3 text-[11px] font-semibold tracking-[0.2em] uppercase hover:bg-white hover:text-primary transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Gửi Yêu Cầu Đặt Món
                    </button>
                </form>
            </div>
        </div>

    </div>

    @push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderCart', () => ({
                cartOpen: false,
                items: [],
                allFoods: @json($allFoodsList),
                categories: @json($catList),
                activeCategory: 'all',
                currentPage: 1,
                pageSize: 9,
                sortBy: 'latest',
                minPrice: 0,
                maxPrice: 1000,
                filterMinPrice: 0,
                filterMaxPrice: 1000,
                
                get filteredFoods() {
                    let result = [...this.allFoods];
                    
                    if (this.activeCategory !== 'all') {
                        result = result.filter(f => f.category_id === this.activeCategory);
                    }
                    
                    result = result.filter(f => (f.price * 1000) >= this.minPrice && (f.price * 1000) <= this.maxPrice);
                    
                    if (this.sortBy === 'latest') {
                        result.sort((a, b) => b.id - a.id);
                    } else if (this.sortBy === 'price_low') {
                        result.sort((a, b) => a.price - b.price);
                    } else if (this.sortBy === 'price_high') {
                        result.sort((a, b) => b.price - a.price);
                    } else if (this.sortBy === 'popularity' || this.sortBy === 'rating') {
                        // Mock fallback for popularity/rating
                        result.sort((a, b) => a.id - b.id);
                    }
                    
                    return result;
                },
                
                get totalPages() {
                    return Math.ceil(this.filteredFoods.length / this.pageSize) || 1;
                },
                
                get paginatedFoods() {
                    const start = (this.currentPage - 1) * this.pageSize;
                    return this.filteredFoods.slice(start, start + this.pageSize);
                },
                
                setCategory(catId) {
                    this.activeCategory = catId;
                    this.currentPage = 1;
                },
                
                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },
                
                prevPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },
                
                init() {
                    if (this.allFoods.length > 0) {
                        const maxP = Math.max(...this.allFoods.map(f => f.price)) * 1000;
                        this.maxPrice = maxP;
                        this.filterMaxPrice = maxP;
                    }
                    
                    const savedCart = localStorage.getItem('qr_order_cart');
                    if (savedCart) {
                        try {
                            this.items = JSON.parse(savedCart);
                        } catch (e) {
                            this.items = [];
                        }
                    }
                    
                    this.$watch('items', value => {
                        localStorage.setItem('qr_order_cart', JSON.stringify(value));
                    });
                },
                
                clearCart() {
                    this.items = [];
                    localStorage.removeItem('qr_order_cart');
                },
                
                addToCart(id, name, price, image) {
                    const existingIndex = this.items.findIndex(item => item.id === id);
                    if (existingIndex !== -1) {
                        this.items[existingIndex].quantity += 1;
                    } else {
                        this.items.push({ id, name, price, image, quantity: 1 });
                    }
                    this.cartOpen = true;
                },
                
                updateQuantity(index, change) {
                    const newQuantity = this.items[index].quantity + change;
                    if (newQuantity > 0) {
                        this.items[index].quantity = newQuantity;
                    } else {
                        this.removeItem(index);
                    }
                },
                
                removeItem(index) {
                    this.items.splice(index, 1);
                },
                
                totalItems() {
                    return this.items.reduce((total, item) => total + item.quantity, 0);
                },
                
                cartTotal() {
                    return this.items.reduce((total, item) => total + (item.price * item.quantity), 0);
                },
                
                formatPrice(price) {
                    return new Intl.NumberFormat('vi-VN').format(price * 1000) + ' VNĐ';
                }
            }));
        });
    </script>
    @endpush
</x-layouts.app>
