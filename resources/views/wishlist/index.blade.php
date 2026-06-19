<x-layouts.app>
    <!-- Page Header / Breadcrumb Section -->
    <section class="relative w-full pt-[140px] pb-8 px-6 md:px-[60px] lg:px-[120px] bg-[#040810] border-b border-primary/20 z-20">
        <div class="max-w-7xl mx-auto flex flex-col justify-center items-center gap-4">
            <h1 class="text-[22px] md:text-[28px] uppercase tracking-[0.2em] text-primary font-medium text-center">
                MÓN ĂN YÊU THÍCH
            </h1>
            <div class="text-[13px] text-gray-400 font-light tracking-widest flex items-center gap-2">
                <a href="{{ route('welcome') }}" class="hover:text-primary transition-colors">Trang chủ</a>
                <span>»</span>
                <span class="text-white">Yêu thích</span>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="relative w-full py-16 md:py-24 px-6 md:px-[60px] lg:px-[120px] bg-[#040810] z-20 min-h-[50vh]">
        <div class="max-w-7xl mx-auto">
            
            @if($foods->isEmpty())
                <div class="flex flex-col items-center justify-center py-20">
                    <svg class="w-16 h-16 text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    <h2 class="text-xl text-gray-300 font-light tracking-widest mb-4">Bạn chưa lưu món ăn nào</h2>
                    <a href="{{ route('vietnamese_cuisine') }}" class="px-8 py-3 border border-primary text-primary hover:bg-primary hover:text-white transition-colors uppercase tracking-[0.2em] text-sm">
                        Khám phá thực đơn
                    </a>
                </div>
            @else
                <!-- Portfolio Grid -->
                <div id="portfolio-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($foods as $food)
                        <div class="portfolio-item relative aspect-[3/4] overflow-hidden group cursor-pointer" data-category="{{ $food->category->slug ?? '' }}">
                            <!-- Image -->
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
                            <img src="{{ $imageUrl }}" alt="{{ $food->name }}" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            
                            <!-- Hover Overlay -->
                            <div class="absolute inset-4 bg-[#0a0f18]/95 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-center items-center text-center p-6 border border-primary/20">
                                <h3 class="text-[15px] tracking-[0.2em] uppercase text-primary font-medium mb-3 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                    {{ $food->name }}
                                </h3>
                                <span class="text-[13px] text-gray-400 font-light tracking-widest transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 delay-75">
                                    {{ $food->category->name ?? 'Món ngon' }}
                                </span>
                                
                                <a href="{{ route('vietnamese_cuisine_detail', ['slug' => $food->slug]) }}" class="absolute inset-0 z-10">
                                    <span class="sr-only">Xem chi tiết {{ $food->name }}</span>
                                </a>
                            </div>

                            <!-- Heart Icon -->
                            <button class="absolute top-6 right-6 z-20 w-10 h-10 bg-black/40 backdrop-blur-sm rounded-full flex items-center justify-center text-red-500 hover:scale-110 transition-transform btn-wishlist" data-id="{{ $food->id }}">
                                <svg class="w-5 h-5 heart-icon" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>


</x-layouts.app>
