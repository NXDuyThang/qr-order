<x-layouts.app>
    <!-- Page Header / Breadcrumb Section -->
    <section class="relative w-full pt-[140px] pb-8 px-6 md:px-[60px] lg:px-[120px] bg-[#040810] border-b border-primary/20 z-20">
        <div class="max-w-7xl mx-auto flex flex-col justify-center items-center gap-4">
            <h1 class="text-[22px] md:text-[28px] uppercase tracking-[0.2em] text-primary font-medium text-center">
                ẨM THỰC VIỆT
            </h1>
            <div class="text-[13px] text-gray-400 font-light tracking-widest flex items-center gap-2">
                <a href="{{ route('welcome') }}" class="hover:text-primary transition-colors">Trang chủ</a>
                <span>»</span>
                <span class="text-white">Ẩm thực Việt</span>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="relative w-full py-16 md:py-24 px-6 md:px-[60px] lg:px-[120px] bg-[#040810] z-20">
        <div class="max-w-7xl mx-auto">
            
            <!-- Filters -->
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12 mb-16">
                <a href="{{ route('vietnamese_cuisine', ['category' => 'all']) }}" class="filter-btn text-[14px] font-medium tracking-[0.15em] {{ (!request('category') || request('category') == 'all') ? 'text-primary' : 'text-gray-400' }} transition-colors hover:text-primary relative group">
                    Tất cả
                    <span class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-primary rounded-full transition-opacity duration-300 {{ (!request('category') || request('category') == 'all') ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }}"></span>
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('vietnamese_cuisine', ['category' => $category->slug]) }}" class="filter-btn text-[14px] font-medium tracking-[0.15em] {{ request('category') == $category->slug ? 'text-primary' : 'text-gray-400' }} transition-colors hover:text-primary relative group">
                        {{ $category->name }}
                        <span class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-primary rounded-full transition-opacity duration-300 {{ request('category') == $category->slug ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }}"></span>
                    </a>
                @endforeach
            </div>

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
                            
                            <!-- Optional link to detail -->
                            <a href="{{ route('vietnamese_cuisine_detail', ['slug' => $food->slug]) }}" class="absolute inset-0 z-10">
                                <span class="sr-only">Xem chi tiết {{ $food->name }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($foods->hasPages())
            <div class="mt-20 flex justify-center items-center gap-6">
                {{-- Previous Page Link --}}
                @if (!$foods->onFirstPage())
                    <a href="{{ $foods->previousPageUrl() }}" class="w-10 h-10 rounded-full border border-transparent text-gray-400 flex justify-center items-center text-[16px] hover:text-primary transition-colors pointer-events-auto">
                        «
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($foods->getUrlRange(1, $foods->lastPage()) as $page => $url)
                    @if ($page == $foods->currentPage())
                        <button class="w-10 h-10 rounded-full border border-primary text-primary flex justify-center items-center text-[12px] hover:bg-primary hover:text-white transition-colors pointer-events-auto">
                            {{ $page }}
                        </button>
                    @else
                        <a href="{{ $url }}" class="w-10 h-10 rounded-full border border-transparent text-gray-400 flex justify-center items-center text-[12px] hover:border-primary/50 hover:text-white transition-colors pointer-events-auto">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($foods->hasMorePages())
                    <a href="{{ $foods->nextPageUrl() }}" class="w-10 h-10 rounded-full border border-transparent text-gray-400 flex justify-center items-center text-[16px] hover:text-primary transition-colors pointer-events-auto">
                        »
                    </a>
                @endif
            </div>
            @endif
        </div>
    </section>

    <!-- Booking Section matching Home Page -->
    <section class="relative w-full py-24 px-6 md:px-[120px] bg-transparent z-20 border-t border-primary/20">
        <form action="{{ route('booking') }}" method="GET" class="max-w-5xl mx-auto flex flex-col md:flex-row gap-6 justify-center items-center">
            <!-- Guests -->
            <div class="relative w-full md:w-64">
                <select name="guests" class="w-full h-[60px] border border-primary/30 px-6 appearance-none bg-[#040810]/80 backdrop-blur-sm text-[16px] tracking-[0.1em] text-white hover:border-primary transition-colors focus:outline-none focus:border-primary cursor-pointer">
                    <option value="1">1 Người</option>
                    <option value="2" selected>2 Người</option>
                    <option value="3">3 Người</option>
                    <option value="4">4 Người</option>
                    <option value="5">5+ Người</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-6 pointer-events-none">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
            <!-- Date -->
            <div class="relative w-full md:w-64">
                <input type="date" name="date" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" onclick="this.showPicker()" class="w-full h-[60px] border border-primary/30 px-6 bg-[#040810]/80 backdrop-blur-sm text-[16px] tracking-[0.1em] text-white hover:border-primary transition-colors focus:outline-none focus:border-primary [&::-webkit-calendar-picker-indicator]:invert cursor-pointer">
            </div>
            <!-- Time -->
            <div class="relative w-full md:w-64">
                <input type="time" name="time" required value="19:00" onclick="this.showPicker()" class="w-full h-[60px] border border-primary/30 px-6 bg-[#040810]/80 backdrop-blur-sm text-[16px] tracking-[0.1em] text-white hover:border-primary transition-colors focus:outline-none focus:border-primary [&::-webkit-calendar-picker-indicator]:invert cursor-pointer">
            </div>
            <!-- Button -->
            <button type="submit" class="w-full md:w-auto h-[60px] px-12 border border-primary text-[13px] uppercase tracking-[0.3em] font-medium text-white hover:bg-primary transition-all duration-300 bg-transparent cursor-pointer flex items-center justify-center whitespace-nowrap">
                ĐẶT BÀN NGAY
            </button>
        </form>
        <div class="max-w-5xl mx-auto mt-4 flex justify-start pl-0 md:pl-2">
            <span class="text-gray-500 text-[11px] font-light tracking-wide">*Cung cấp bởi QR Order</span>
        </div>
    </section>

    @push('scripts')
    <script>
        // Document load event can go here if needed in future
    </script>
    @endpush
</x-layouts.app>
