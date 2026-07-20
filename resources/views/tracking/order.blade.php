<x-layouts.app>
    @push('styles')
    <style>
        .tracking-container {
            font-family: var(--font-sans, 'Jost', sans-serif);
        }
        .section-title {
            font-family: var(--font-serif, 'Playfair Display', serif);
        }
        
        /* Status timeline */
        .timeline {
            position: relative;
            max-width: 400px;
            margin: 0 auto;
        }
        .timeline::before {
            content: '';
            position: absolute;
            top: 0;
            left: 20px;
            height: 100%;
            width: 2px;
            background: rgba(255, 255, 255, 0.1);
        }
        .timeline-step {
            position: relative;
            padding-left: 60px;
            margin-bottom: 40px;
            opacity: 0.5;
            transition: all 0.5s ease;
        }
        .timeline-step:last-child {
            margin-bottom: 0;
        }
        .timeline-step.active, .timeline-step.completed {
            opacity: 1;
        }
        .step-indicator {
            position: absolute;
            left: 10px;
            top: 5px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #0d1114;
            border: 2px solid rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            transition: all 0.5s ease;
        }
        .timeline-step.active .step-indicator {
            border-color: #0077bb;
            box-shadow: 0 0 10px rgba(0, 119, 187, 0.5);
        }
        .timeline-step.active .step-indicator::after {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #0077bb;
        }
        .timeline-step.completed .step-indicator {
            border-color: #0077bb;
            background: #0077bb;
        }
        .timeline-step.completed .step-indicator::after {
            content: '✓';
            color: white;
            font-size: 12px;
            line-height: 1;
        }
        .step-title {
            color: white;
            font-size: 16px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        .step-desc {
            color: #9ca3af;
            font-size: 13px;
            margin-top: 4px;
        }
    </style>
    @endpush

    @php
        $allServed = $order->items->whereNotIn('status', ['served', 'completed', 'cancelled'])->count() === 0;
    @endphp

    <div class="pt-[110px] pb-24 min-h-screen bg-[#0d1114] tracking-container" 
         x-data="orderTracker()" 
         x-init="initTracker()">
        
        <div class="max-w-3xl mx-auto px-6">
            
            <div class="text-center mb-16 border-b border-white/5 pb-8">
                <h1 class="text-[24px] md:text-[32px] uppercase tracking-[0.2em] text-primary font-medium section-title">
                    TRẠNG THÁI ĐƠN HÀNG
                </h1>
                <p class="text-gray-400 tracking-wider mt-2 text-sm">Mã đơn: #{{ $order->id }} - Bàn: {{ $order->table_id }}</p>
            </div>

            <div class="bg-[#040810] border border-white/5 p-8 relative overflow-hidden rounded-xl mb-12">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-5 pointer-events-none"></div>
                
                <!-- Status Timeline -->
                <div class="timeline relative z-10 py-4">
                    <!-- Step 1: New -->
                    <div class="timeline-step" :class="{ 'completed': status !== 'new', 'active': status === 'new' }">
                        <div class="step-indicator"></div>
                        <h3 class="step-title">Đã tiếp nhận</h3>
                        <p class="step-desc">Bếp đang chuẩn bị món ăn của bạn.</p>
                    </div>
                    
                    <!-- Step 2: Ready -->
                    <div class="timeline-step" :class="{ 'completed': status === 'served' || status === 'completed', 'active': status === 'ready' }">
                        <div class="step-indicator"></div>
                        <h3 class="step-title">Nấu xong</h3>
                        <p class="step-desc">Nhân viên phục vụ đang mang món ra bàn.</p>
                    </div>
                    
                    <!-- Step 3: Served -->
                    <div class="timeline-step" :class="{ 'completed': status === 'completed', 'active': status === 'served' }">
                        <div class="step-indicator"></div>
                        <h3 class="step-title">Đã giao món</h3>
                        <p class="step-desc">Chúc quý khách ngon miệng!</p>
                    </div>
                    
                    <!-- Step 4: Payment -->
                    <div class="timeline-step" :class="{ 'completed': paymentStatus === 'paid', 'active': status === 'served' && paymentStatus === 'pending' }">
                        <div class="step-indicator"></div>
                        <h3 class="step-title">Thanh toán</h3>
                        <p class="step-desc">
                            <span x-show="paymentStatus === 'pending'">Đang chờ thanh toán</span>
                            <span x-show="paymentStatus === 'paid'" class="text-green-500">Đã thanh toán thành công</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Order Items List -->
            <div class="bg-[#040810] border border-white/5 p-6 md:p-8 rounded-xl mb-12 relative overflow-hidden">
                <h2 class="text-white text-lg font-medium tracking-wider mb-6 border-b border-white/10 pb-4">Chi tiết các món đã gọi</h2>
                
                @if(session('success'))
                    <div class="bg-green-900/30 text-green-400 border border-green-500/30 px-4 py-3 rounded mb-6 text-sm">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-900/30 text-red-400 border border-red-500/30 px-4 py-3 rounded mb-6 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center p-4 border border-white/5 bg-[#0d1114] rounded-lg gap-4">
                            <div>
                                <h3 class="text-white text-md tracking-wide">{{ $item->food->name }}</h3>
                                @if($item->food->preparation_time)
                                    <p class="text-gray-400 text-xs mt-1">⏳ Thời gian làm: {{ $item->food->preparation_time }} phút</p>
                                @endif
                                <p class="text-gray-400 text-sm mt-1">
                                    Số lượng: {{ $item->quantity }} x {{ number_format($item->unit_price * 1000, 0, ',', '.') }} VNĐ 
                                    = <strong class="text-primary">{{ number_format($item->quantity * $item->unit_price * 1000, 0, ',', '.') }} VNĐ</strong>
                                </p>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="px-3 py-1 rounded text-xs font-semibold uppercase tracking-wider 
                                    {{ $item->status === 'new' ? 'bg-primary/20 text-primary border border-primary/30' : '' }}
                                    {{ $item->status === 'preparing' ? 'bg-warning/20 text-warning border border-warning/30 text-orange-400' : '' }}
                                    {{ $item->status === 'ready' ? 'bg-info/20 text-info border border-info/30 text-blue-400' : '' }}
                                    {{ $item->status === 'served' ? 'bg-success/20 text-success border border-success/30 text-green-400' : '' }}
                                    {{ $item->status === 'completed' ? 'bg-gray-800/80 text-gray-300 border border-gray-600' : '' }}
                                    {{ $item->status === 'cancelled' ? 'bg-danger/20 text-danger border border-danger/30 text-red-400' : '' }}
                                ">
                                    @switch($item->status)
                                        @case('new') Đang đợi bếp @break
                                        @case('preparing') Đang nấu @break
                                        @case('ready') Nấu xong @break
                                        @case('served') Đã lên món @break
                                        @case('completed') Đã hoàn tất @break
                                        @case('cancelled') Đã huỷ @break
                                        @default {{ $item->status }}
                                    @endswitch
                                </span>
                                
                                @if($item->status === 'new')
                                    <form action="{{ route('order.item.cancel', ['order' => $order->id, 'item' => $item->id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn huỷ món này không?');">
                                        @csrf
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-sm tracking-wide">Huỷ món</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-8 pt-6 border-t border-white/10 flex justify-between items-center">
                    <span class="text-gray-400 uppercase tracking-widest text-sm">Tổng cộng:</span>
                    <span class="text-2xl text-white font-serif">{{ number_format($order->total_price * 1000, 0, ',', '.') }} VNĐ</span>
                </div>
            </div>

            <!-- Action Area -->
            <div class="text-center relative z-10 transition-all duration-500 mt-12" style="display: none;" x-show="paymentStatus === 'pending' && allItemsServed" x-data="{ showPaymentOptions: false }">
                <h3 class="text-xl text-white font-serif mb-6 tracking-[0.1em] uppercase">Thanh toán Hoá đơn</h3>
                
                @if(!$order->payment_method)
                    <div x-show="!showPaymentOptions">
                        <button @click="showPaymentOptions = true" class="inline-block bg-primary text-white border border-primary px-10 py-4 text-[13px] font-semibold tracking-[0.2em] uppercase hover:bg-white hover:text-primary transition-colors shadow-[0_0_15px_rgba(0,119,187,0.3)]">
                            Thanh Toán
                        </button>
                    </div>

                    <div x-show="showPaymentOptions" style="display: none;" x-transition>
                        <p class="text-gray-400 mb-6 text-sm">Vui lòng chọn phương thức thanh toán</p>
                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <form action="{{ route('order.update_payment_method', ['order' => $order->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="payment_method" value="cash">
                                <button type="submit" class="w-full sm:w-auto inline-block bg-[#0d1114] text-white border border-white/20 px-8 py-3 text-[13px] font-semibold tracking-[0.2em] uppercase hover:bg-white hover:text-[#0d1114] transition-colors">
                                    Tiền Mặt
                                </button>
                            </form>
                            <form action="{{ route('order.update_payment_method', ['order' => $order->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="payment_method" value="transfer">
                                <button type="submit" class="w-full sm:w-auto inline-block bg-primary text-white border border-primary px-8 py-3 text-[13px] font-semibold tracking-[0.2em] uppercase hover:bg-white hover:text-primary transition-colors shadow-[0_0_15px_rgba(0,119,187,0.3)]">
                                    Chuyển Khoản
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    @if($order->payment_method === 'transfer')
                        <p class="text-white mb-6 tracking-wide">Vui lòng quét mã QR để thanh toán.</p>
                        <a href="{{ route('checkout.transfer', ['order' => $order->id]) }}" class="inline-block bg-primary text-white px-10 py-4 text-[13px] font-semibold tracking-[0.2em] uppercase hover:bg-white hover:text-primary transition-colors shadow-[0_0_15px_rgba(0,119,187,0.3)] hover:shadow-[0_0_20px_rgba(255,255,255,0.4)]">
                            Mã QR Thanh Toán
                        </a>
                    @elseif($order->payment_method === 'cash')
                        <div class="bg-gray-800/50 rounded-xl p-6 border border-white/10 backdrop-blur-md">
                            <h4 class="text-white text-lg font-serif mb-2 tracking-wider">Thanh toán Tiền mặt</h4>
                            <p class="text-gray-300 text-sm leading-relaxed mb-4">Vui lòng thanh toán tại quầy thu ngân.</p>
                            <p class="text-white font-semibold text-xl">{{ number_format($order->total_price * 1000, 0, ',', '.') }} VNĐ</p>
                        </div>
                    @endif
                @endif
            </div>

            <div class="text-center relative z-10 transition-all duration-500 mt-12" x-show="paymentStatus === 'paid'" style="display: none;" x-transition.opacity>
                <div class="bg-green-900/20 border border-green-500/30 text-green-400 p-6 rounded-xl inline-block">
                    <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-lg tracking-widest uppercase">Cảm ơn quý khách</p>
                    <p class="text-sm mt-2 text-green-500/70">Thanh toán đã được xác nhận.</p>
                </div>
                <div class="mt-8">
                    <a href="{{ route('welcome') }}" class="text-gray-400 hover:text-white transition-colors text-sm border-b border-transparent hover:border-white pb-1">Trở về trang chủ</a>
                </div>
            </div>

            <!-- Back to Menu or Add More -->
            <!-- Order Actions -->
            @if(!$order->payment_method)
            <div class="mt-8 flex gap-4 justify-center relative z-10" x-show="status !== 'completed'">
                <a href="{{ route('order_at_table', ['table_id' => $order->table_id]) }}" class="inline-block bg-transparent text-white border border-white px-8 py-3 text-[13px] font-semibold tracking-[0.2em] uppercase hover:bg-white hover:text-black transition-colors">
                    Gọi thêm món
                </a>
            </div>
            @endif
            
        </div>
    </div>

    @push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderTracker', () => ({
                status: '{{ $order->status }}',
                paymentStatus: '{{ $order->payment_status }}',
                allItemsServed: {{ $allServed ? 'true' : 'false' }},
                orderId: {{ $order->id }},
                pollInterval: null,

                initTracker() {
                    this.pollInterval = setInterval(() => {
                        this.fetchStatus();
                    }, 3000); // Fetch every 3 seconds
                },

                fetchStatus() {
                    fetch(`/api/order/${this.orderId}/status`)
                        .then(response => response.json())
                        .then(data => {
                            this.status = data.status;
                            this.paymentStatus = data.payment_status;
                            this.allItemsServed = data.all_items_served;
                            
                            // Stop polling if completed and paid
                            if (this.status === 'completed' && this.paymentStatus === 'paid') {
                                clearInterval(this.pollInterval);
                            }
                        })
                        .catch(err => console.error('Error fetching status:', err));
                }
            }));
        });
    </script>
    @endpush
</x-layouts.app>
