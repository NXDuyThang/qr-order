<x-layouts.app>
    <div class="min-h-screen bg-[#040810] pb-24 px-6">
        <div class="max-w-4xl mx-auto pt-8">
            <div class="text-center mb-16">
                <h3 class="font-script-tagline text-[40px] md:text-[50px] text-primary mb-2">Đặt bàn</h3>
                <h2 class="section-title-deco mb-6">GIỮ CHỖ NGAY</h2>
                <p class="text-gray-400 font-light max-w-2xl mx-auto">Vui lòng điền thông tin bên dưới để chúng tôi chuẩn bị đón tiếp bạn một cách chu đáo nhất.</p>
            </div>

            @if(session('success'))
                <div class="bg-[#0a0f16] border border-primary text-primary px-6 py-6 mb-10 text-center shadow-lg">
                    <p class="text-lg">{{ session('success') }}</p>
                    <a href="{{ route('welcome') }}" class="inline-block mt-4 text-sm text-gray-400 hover:text-white underline">Quay lại trang chủ</a>
                </div>
            @endif

            <div class="bg-[#0a0f16] border border-primary/20 p-8 md:p-12 shadow-2xl relative">
                <!-- Decorative corners -->
                <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-primary/50"></div>
                <div class="absolute top-0 right-0 w-8 h-8 border-t border-r border-primary/50"></div>
                <div class="absolute bottom-0 left-0 w-8 h-8 border-b border-l border-primary/50"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-primary/50"></div>

                <form action="{{ route('booking.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Tên -->
                        <div class="md:col-span-2">
                            <label class="block text-gray-400 text-sm tracking-widest uppercase mb-3">Tên của bạn *</label>
                            <input type="text" name="name" required class="w-full bg-transparent border border-primary/30 py-3 px-4 text-white focus:outline-none focus:border-primary transition-colors">
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- SĐT -->
                        <div>
                            <label class="block text-gray-400 text-sm tracking-widest uppercase mb-3">Số điện thoại *</label>
                            <input type="tel" name="phone" required class="w-full bg-transparent border border-primary/30 py-3 px-4 text-white focus:outline-none focus:border-primary transition-colors">
                            @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Số người -->
                        <div>
                            <label class="block text-gray-400 text-sm tracking-widest uppercase mb-3">Số người *</label>
                            <div class="relative">
                                <select name="guests" class="w-full bg-[#040810] border border-primary/30 py-3 px-4 text-white appearance-none focus:outline-none focus:border-primary transition-colors">
                                    <option value="1" {{ request('guests') == 1 ? 'selected' : '' }}>1 Người</option>
                                    <option value="2" {{ request('guests', 2) == 2 ? 'selected' : '' }}>2 Người</option>
                                    <option value="3" {{ request('guests') == 3 ? 'selected' : '' }}>3 Người</option>
                                    <option value="4" {{ request('guests') == 4 ? 'selected' : '' }}>4 Người</option>
                                    <option value="5" {{ request('guests') == 5 ? 'selected' : '' }}>5+ Người</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                                    <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Ngày -->
                        <div>
                            <label class="block text-gray-400 text-sm tracking-widest uppercase mb-3">Ngày *</label>
                            <input type="date" name="date" required value="{{ request('date', date('Y-m-d')) }}" class="w-full bg-transparent border border-primary/30 py-3 px-4 text-gray-300 focus:outline-none focus:border-primary transition-colors [&::-webkit-calendar-picker-indicator]:invert">
                            @error('date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Giờ -->
                        <div>
                            <label class="block text-gray-400 text-sm tracking-widest uppercase mb-3">Giờ *</label>
                            <input type="time" name="time" required value="{{ request('time', '19:00') }}" class="w-full bg-transparent border border-primary/30 py-3 px-4 text-gray-300 focus:outline-none focus:border-primary transition-colors [&::-webkit-calendar-picker-indicator]:invert">
                            @error('time') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Ghi chú -->
                    <div>
                        <label class="block text-gray-400 text-sm tracking-widest uppercase mb-3">Ghi chú thêm</label>
                        <textarea name="notes" rows="3" placeholder="Ví dụ: Cần ghế trẻ em, dị ứng đậu phộng, vị trí gần cửa sổ..." class="w-full bg-transparent border border-primary/30 py-3 px-4 text-white focus:outline-none focus:border-primary transition-colors"></textarea>
                    </div>

                    <div class="text-center pt-8">
                        <button type="submit" class="inline-block px-12 py-4 border border-primary text-white text-[13px] tracking-[0.3em] uppercase hover:bg-primary transition-colors duration-300">
                            XÁC NHẬN ĐẶT BÀN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
