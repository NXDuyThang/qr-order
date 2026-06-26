<x-layouts.app>
    <div class="pt-[110px] pb-24 min-h-screen bg-[#0d1114] flex flex-col items-center justify-center">
        
        <div class="max-w-xl w-full mx-auto px-6">
            
            <div class="text-center mb-10">
                <h1 class="text-[28px] md:text-[32px] uppercase tracking-[0.2em] text-primary font-medium" style="font-family: var(--font-serif, 'Playfair Display', serif);">
                    Quét Mã Thanh Toán
                </h1>
                <p class="text-gray-400 tracking-wider mt-2 text-sm">Đơn hàng #{{ $order->id }} - Bàn số {{ $order->table_id }}</p>
            </div>

            <div class="bg-[#040810] border border-white/10 p-8 md:p-12 relative overflow-hidden shadow-2xl rounded-sm">
                <!-- Decorative bg -->
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-5 pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col items-center">
                    
                    <!-- QR Code Image -->
                    <div class="bg-white p-4 rounded-xl shadow-lg mb-8 max-w-[300px] w-full">
                        <img src="{{ $qrUrl }}" alt="VietQR" class="w-full h-auto object-contain">
                    </div>

                    <!-- Payment Info -->
                    <div class="w-full space-y-4 border-t border-white/10 pt-8">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm tracking-wider">Ngân hàng:</span>
                            <span class="text-white tracking-wide font-medium">{{ $bankId }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm tracking-wider">Số tài khoản:</span>
                            <span class="text-white tracking-wide font-medium">{{ $accountNo }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm tracking-wider">Chủ tài khoản:</span>
                            <span class="text-white tracking-wide font-medium">{{ $accountName }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm tracking-wider">Số tiền:</span>
                            <span class="text-primary font-bold tracking-wider text-lg">{{ number_format($amount, 0, ',', '.') }} VNĐ</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm tracking-wider">Nội dung:</span>
                            <span class="text-white tracking-wide bg-white/5 px-3 py-1 rounded border border-white/10">{{ $addInfo }}</span>
                        </div>
                    </div>

                    <div class="w-full mt-10">
                        <a href="{{ route('order_at_table', ['table_id' => $order->table_id]) }}" class="block text-center w-full bg-primary text-white py-4 text-[13px] font-semibold tracking-[0.2em] uppercase hover:bg-white hover:text-primary transition-colors relative z-10 shadow-[0_0_15px_rgba(0,119,187,0.3)] hover:shadow-[0_0_20px_rgba(255,255,255,0.4)]">
                            Tôi Đã Thanh Toán Thành Công
                        </a>
                        <p class="text-center text-gray-500 text-[12px] mt-4 tracking-wide">
                            Nhân viên sẽ kiểm tra và xác nhận đơn hàng của bạn ngay lập tức.
                        </p>
                    </div>

                </div>
            </div>
            
        </div>
    </div>
</x-layouts.app>
