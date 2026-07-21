<x-layouts.app>
    @push('styles')
    <style>
        .tracking-container {
            font-family: var(--font-sans, 'Jost', sans-serif);
        }
        .section-title {
            font-family: var(--font-serif, 'Playfair Display', serif);
        }
        
        /* Custom Scrollbar for Horizontal Timeline */
        .timeline-scroll::-webkit-scrollbar {
            height: 4px;
        }
        .timeline-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        .timeline-scroll::-webkit-scrollbar-thumb {
            background: rgba(0, 119, 187, 0.5);
            border-radius: 4px;
        }
    </style>
    @endpush

    @php
        $allServed = $order->items->whereNotIn('status', ['served', 'completed', 'cancelled'])->count() === 0;
    @endphp

    <div class="pt-[110px] pb-24 min-h-screen bg-[#0d1114] tracking-container" 
         x-data="orderTracker()" 
         x-init="initTracker()">
        
        <div class="max-w-4xl mx-auto px-4 md:px-6">
            
            <div class="text-center mb-10 md:mb-16 border-b border-white/5 pb-8">
                <h1 class="text-[20px] md:text-[32px] uppercase tracking-[0.2em] text-primary font-medium section-title">
                    TRẠNG THÁI ĐƠN HÀNG
                </h1>
                <p class="text-gray-400 tracking-wider mt-2 text-sm">Mã đơn: #{{ $order->id }} - Bàn: {{ $order->table_id }}</p>
            </div>

            <!-- Horizontal Status Timeline -->
            <div class="bg-[#040810] border border-white/5 p-6 md:p-10 relative overflow-hidden rounded-xl mb-12 shadow-2xl">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-5 pointer-events-none"></div>
                
                <div class="relative z-10 w-full overflow-x-auto timeline-scroll pb-4">
                    <div class="min-w-[600px] flex justify-between items-center relative pt-6 pb-2 px-4 md:px-10">
                        
                        <!-- Connecting Line Background -->
                        <div class="absolute top-[38px] left-[10%] right-[10%] h-1 bg-white/10 -translate-y-1/2 z-0 rounded"></div>
                        
                        <!-- Connecting Line Active (Dynamic width based on status) -->
                        <div class="absolute top-[38px] left-[10%] h-1 bg-primary -translate-y-1/2 z-0 rounded transition-all duration-1000 ease-in-out" 
                             :style="{ width: getProgressWidth() }"></div>
                        
                        <!-- Step 1: New -->
                        <div class="relative z-10 flex flex-col items-center group" :class="{ 'opacity-100': true }">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 bg-[#040810] transition-all duration-500"
                                 :class="(status === 'new' || status !== 'new') ? 'border-primary shadow-[0_0_15px_rgba(0,119,187,0.5)]' : 'border-white/20'">
                                <div class="w-4 h-4 rounded-full transition-all duration-500"
                                     :class="(status === 'new' || status !== 'new') ? 'bg-primary' : 'bg-transparent'"></div>
                            </div>
                            <h3 class="mt-4 text-[13px] md:text-sm font-medium tracking-widest uppercase transition-colors"
                                :class="(status === 'new' || status !== 'new') ? 'text-white' : 'text-gray-500'">Đã tiếp nhận</h3>
                        </div>
                        
                        <!-- Step 2: Ready -->
                        <div class="relative z-10 flex flex-col items-center group transition-all duration-500" 
                             :class="(status === 'ready' || status === 'served' || status === 'completed') ? 'opacity-100' : 'opacity-50'">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 bg-[#040810] transition-all duration-500"
                                 :class="(status === 'ready' || status === 'served' || status === 'completed') ? 'border-primary shadow-[0_0_15px_rgba(0,119,187,0.5)]' : 'border-white/20'">
                                <div class="w-4 h-4 rounded-full transition-all duration-500"
                                     :class="(status === 'ready' || status === 'served' || status === 'completed') ? 'bg-primary' : 'bg-transparent'"></div>
                            </div>
                            <h3 class="mt-4 text-[13px] md:text-sm font-medium tracking-widest uppercase transition-colors"
                                :class="(status === 'ready' || status === 'served' || status === 'completed') ? 'text-white' : 'text-gray-500'">Nấu xong</h3>
                        </div>
                        
                        <!-- Step 3: Served -->
                        <div class="relative z-10 flex flex-col items-center group transition-all duration-500"
                             :class="(status === 'served' || status === 'completed') ? 'opacity-100' : 'opacity-50'">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 bg-[#040810] transition-all duration-500"
                                 :class="(status === 'served' || status === 'completed') ? 'border-primary shadow-[0_0_15px_rgba(0,119,187,0.5)]' : 'border-white/20'">
                                <div class="w-4 h-4 rounded-full transition-all duration-500"
                                     :class="(status === 'served' || status === 'completed') ? 'bg-primary' : 'bg-transparent'"></div>
                            </div>
                            <h3 class="mt-4 text-[13px] md:text-sm font-medium tracking-widest uppercase transition-colors"
                                :class="(status === 'served' || status === 'completed') ? 'text-white' : 'text-gray-500'">Đã giao món</h3>
                        </div>
                        
                        <!-- Step 4: Payment -->
                        <div class="relative z-10 flex flex-col items-center group transition-all duration-500"
                             :class="(paymentStatus === 'paid') ? 'opacity-100' : 'opacity-50'">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 bg-[#040810] transition-all duration-500"
                                 :class="(paymentStatus === 'paid') ? 'border-primary shadow-[0_0_15px_rgba(0,119,187,0.5)] bg-primary' : 'border-white/20'">
                                <svg x-show="paymentStatus === 'paid'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                <div x-show="paymentStatus !== 'paid'" class="w-4 h-4 rounded-full bg-transparent"></div>
                            </div>
                            <h3 class="mt-4 text-[13px] md:text-sm font-medium tracking-widest uppercase transition-colors"
                                :class="(paymentStatus === 'paid') ? 'text-primary' : 'text-gray-500'">Thanh toán</h3>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Dynamic Status Subtext -->
                <div class="text-center mt-6 h-6">
                    <p x-show="status === 'new'" class="text-gray-400 text-sm tracking-wide animate-pulse">Bếp đang chuẩn bị món ăn của bạn...</p>
                    <p x-show="status === 'ready'" class="text-gray-400 text-sm tracking-wide animate-pulse">Nhân viên phục vụ đang mang món ra bàn...</p>
                    <p x-show="status === 'served' && paymentStatus !== 'paid'" class="text-gray-400 text-sm tracking-wide">Chúc quý khách ngon miệng! Đang chờ thanh toán.</p>
                    <p x-show="paymentStatus === 'paid'" class="text-green-400 text-sm tracking-wide font-medium">Đã thanh toán thành công.</p>
                </div>
            </div>

            <!-- Order Items List -->
            <div class="bg-[#040810] border border-white/5 p-6 md:p-8 rounded-xl mb-12 relative overflow-hidden shadow-2xl">
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

                <div id="order-items-container" class="space-y-4">
                    @foreach($order->items as $item)
                        <div x-data="{ expanded: false }" class="bg-[#0d1114] border border-white/5 rounded-lg overflow-hidden transition-all duration-300 shadow-md">
                            <!-- Summary Header (Clickable) -->
                            <div @click="expanded = !expanded" class="flex justify-between items-center p-4 md:p-5 cursor-pointer hover:bg-white/5 transition-colors">
                                <div class="flex items-center gap-4 flex-grow">
                                    <div class="w-12 h-12 rounded-md bg-gray-800 shrink-0 overflow-hidden border border-white/10">
                                        <img src="{{ $item->food->image ?? '/images/default-food.jpg' }}" alt="{{ $item->food->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-grow">
                                        <h3 class="text-white text-sm md:text-md tracking-wide font-medium">{{ $item->food->name }} <span class="text-gray-400 font-normal ml-1">x{{ $item->quantity }}</span></h3>
                                        <div class="mt-2 flex flex-wrap gap-2 items-center">
                                            <span class="px-2 py-0.5 rounded text-[10px] md:text-xs font-semibold uppercase tracking-wider 
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
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-4 shrink-0">
                                    <span class="text-primary font-medium text-sm md:text-md whitespace-nowrap hidden sm:inline">{{ number_format($item->quantity * $item->unit_price * 1000, 0, ',', '.') }} đ</span>
                                    <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center border border-white/10">
                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Expanded Details -->
                            <div x-show="expanded" x-collapse.duration.300ms class="border-t border-white/5 bg-[#080b0e]" style="display: none;">
                                <div class="p-4 md:p-5">
                                    <div class="flex justify-between text-sm text-gray-400 mb-3">
                                        <span>Đơn giá:</span>
                                        <span>{{ number_format($item->unit_price * 1000, 0, ',', '.') }} VNĐ</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-400 mb-6">
                                        <span>Tổng tiền:</span>
                                        <span class="text-white font-medium">{{ number_format($item->quantity * $item->unit_price * 1000, 0, ',', '.') }} VNĐ</span>
                                    </div>
                                    
                                    @if($item->status === 'new')
                                        <div class="flex gap-3 justify-end pt-4 border-t border-white/5">
                                            @if($item->quantity > 1)
                                                <form action="{{ route('order.item.reduce', ['order' => $order->id, 'item' => $item->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn giảm 1 số lượng của món này không?');">
                                                    @csrf
                                                    <button type="submit" class="px-4 py-2 border border-warning/50 text-orange-400 hover:bg-warning/10 text-xs font-semibold tracking-wider uppercase rounded transition-colors flex items-center gap-2">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                                        Giảm 1 SL
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('order.item.cancel', ['order' => $order->id, 'item' => $item->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn huỷ toàn bộ món này không?');">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-red-900/30 border border-red-500/50 text-red-400 hover:bg-red-900/50 hover:text-red-300 text-xs font-semibold tracking-wider uppercase rounded transition-colors flex items-center gap-2">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    Huỷ món
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="pt-4 border-t border-white/5 text-right">
                                            <span class="text-gray-500 text-xs italic">Không thể huỷ hay giảm số lượng khi bếp đã bắt đầu xử lý.</span>
                                        </div>
                                    @endif
                                </div>
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
                            <p class="text-gray-300 text-sm leading-relaxed mb-4">Cảm ơn khách hàng đã thanh toán.</p>
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
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
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

                getProgressWidth() {
                    if (this.paymentStatus === 'paid') return '80%';
                    if (this.status === 'served' || this.status === 'completed') return '53.33%';
                    if (this.status === 'ready') return '26.66%';
                    return '0%';
                },

                fetchStatus() {
                    fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            
                            const newItems = doc.getElementById('order-items-container');
                            if (newItems) document.getElementById('order-items-container').innerHTML = newItems.innerHTML;
                            
                            fetch(`/api/order/${this.orderId}/status`)
                                .then(res => res.json())
                                .then(data => {
                                    this.status = data.status;
                                    this.paymentStatus = data.payment_status;
                                    this.allItemsServed = data.all_items_served;
                                    if (this.status === 'completed' && this.paymentStatus === 'paid') {
                                        clearInterval(this.pollInterval);
                                    }
                                });
                        })
                        .catch(err => console.error('Error fetching status:', err));
                }
            }));
        });
    </script>
    @endpush
</x-layouts.app>
