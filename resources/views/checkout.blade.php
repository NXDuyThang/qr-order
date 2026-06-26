<x-layouts.app>
    @push('styles')
    <style>
        .checkout-container {
            font-family: var(--font-sans, 'Jost', sans-serif);
        }
        .section-title {
            font-family: var(--font-serif, 'Playfair Display', serif);
        }
        
        /* Custom Radio Button */
        .payment-radio input[type="radio"] {
            display: none;
        }
        .payment-radio .radio-label {
            display: flex;
            align-items: center;
            padding: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(4, 8, 16, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .payment-radio .radio-label:hover {
            border-color: rgba(0, 119, 187, 0.5);
            background: rgba(0, 119, 187, 0.05);
        }
        .payment-radio input[type="radio"]:checked + .radio-label {
            border-color: #0077bb;
            background: rgba(0, 119, 187, 0.1);
        }
        .payment-radio .radio-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.3);
            margin-right: 16px;
            position: relative;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        .payment-radio input[type="radio"]:checked + .radio-label .radio-indicator {
            border-color: #0077bb;
        }
        .payment-radio input[type="radio"]:checked + .radio-label .radio-indicator::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 10px;
            height: 10px;
            background: #0077bb;
            border-radius: 50%;
        }
    </style>
    @endpush

    <div class="pt-[110px] pb-24 min-h-screen bg-[#0d1114] checkout-container">
        
        <div class="max-w-4xl mx-auto px-6">
            
            <div class="text-center mb-12 border-b border-white/5 pb-8">
                <h1 class="text-[28px] md:text-[36px] uppercase tracking-[0.2em] text-primary font-medium section-title">
                    THANH TOÁN
                </h1>
                <p class="text-gray-400 tracking-wider mt-2 text-sm">Hoàn tất thủ tục đặt món tại bàn {{ $tableId }}</p>
            </div>

            @if(session('error'))
            <div class="bg-[#3a1c1e] border border-[#723236] text-[#fc9b9b] px-6 py-4 rounded relative text-sm tracking-wide mb-8">
                {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('order.store') }}" method="POST" class="flex flex-col lg:flex-row gap-12">
                @csrf
                
                <!-- Left: Order Summary -->
                <div class="w-full lg:w-3/5">
                    <h2 class="text-white text-lg tracking-[0.15em] uppercase mb-6 border-b border-white/10 pb-4 flex items-center gap-3">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Tóm Tắt Đơn Hàng
                    </h2>
                    
                    <div class="space-y-6">
                        @php $totalPrice = 0; @endphp
                        @foreach($items as $item)
                            @php $totalPrice += $item['price'] * $item['quantity']; @endphp
                            <div class="flex gap-4 items-center">
                                <div class="w-20 h-20 bg-[#040810] border border-white/5 flex-shrink-0">
                                    <img src="{{ $item['image'] ?? 'https://via.placeholder.com/100x100/1a202c/0077bb?text=Food' }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-white text-[15px] section-title tracking-wider">{{ $item['name'] }}</h4>
                                    <div class="text-gray-400 text-[13px] mt-1">Số lượng: {{ $item['quantity'] }}</div>
                                </div>
                                <div class="text-primary font-medium tracking-wide">
                                    {{ number_format($item['price'] * $item['quantity'] * 1000, 0, ',', '.') }} VNĐ
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right: Payment & Total -->
                <div class="w-full lg:w-2/5">
                    <div class="bg-[#040810] border border-white/5 p-8 relative overflow-hidden">
                        <!-- Decorative bg -->
                        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-5 pointer-events-none"></div>
                        
                        <h2 class="text-white text-lg tracking-[0.15em] uppercase mb-6 border-b border-white/10 pb-4 relative z-10">
                            Thanh Toán
                        </h2>
                        
                        <div class="space-y-4 mb-8 relative z-10">
                            <!-- Cash Option -->
                            <label class="payment-radio block">
                                <input type="radio" name="payment_method" value="cash" checked>
                                <div class="radio-label">
                                    <div class="radio-indicator"></div>
                                    <div>
                                        <div class="text-white text-[14px] uppercase tracking-widest">Tiền mặt</div>
                                        <div class="text-gray-500 text-[12px] mt-1 tracking-wide">Thanh toán cho nhân viên tại bàn</div>
                                    </div>
                                </div>
                            </label>

                            <!-- Transfer Option -->
                            <label class="payment-radio block">
                                <input type="radio" name="payment_method" value="transfer">
                                <div class="radio-label">
                                    <div class="radio-indicator"></div>
                                    <div>
                                        <div class="text-white text-[14px] uppercase tracking-widest">Chuyển khoản</div>
                                        <div class="text-gray-500 text-[12px] mt-1 tracking-wide">Quét mã QR (Nhân viên sẽ xác nhận)</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        
                        <div class="border-t border-white/10 pt-6 pb-6 relative z-10">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-400 text-sm tracking-wider">Tạm tính:</span>
                                <span class="text-white tracking-wide">{{ number_format($totalPrice * 1000, 0, ',', '.') }} VNĐ</span>
                            </div>
                            <div class="flex justify-between items-center mb-6">
                                <span class="text-gray-400 text-sm tracking-wider">Bàn số:</span>
                                <span class="text-white tracking-wide">{{ $tableId }}</span>
                            </div>
                            <div class="flex justify-between items-end border-t border-white/5 pt-4">
                                <span class="text-white tracking-[0.1em] uppercase">Tổng Cộng:</span>
                                <span class="text-primary section-title text-2xl font-bold tracking-wider">{{ number_format($totalPrice * 1000, 0, ',', '.') }} VNĐ</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white py-4 text-[13px] font-semibold tracking-[0.2em] uppercase hover:bg-white hover:text-primary transition-colors relative z-10 shadow-[0_0_15px_rgba(0,119,187,0.3)] hover:shadow-[0_0_20px_rgba(255,255,255,0.4)]">
                            Xác Nhận Đặt Món
                        </button>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</x-layouts.app>
