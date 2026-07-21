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
                    <div class="min-w-[600px] flex justify-between items-start relative pt-2 px-4 md:px-10">
                        
                        <!-- Connecting Line Background and Foreground -->
                        <div class="absolute top-[32px] left-[40px] right-[40px] md:left-[64px] md:right-[64px] h-[2px] bg-[#1a222a] z-0">
                            <div class="absolute top-0 left-0 h-full bg-primary z-0 transition-all duration-1000 ease-in-out shadow-[0_0_10px_rgba(0,119,187,0.8)]" 
                                 :style="{ width: getProgressWidth() }"></div>
                        </div>
                        
                        <!-- Step 1: New -->
                        <div class="relative z-10 flex flex-col items-center w-28 group" :class="{ 'opacity-100': true }">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center border-[2px] bg-[#040810] transition-all duration-500"
                                 :class="(status === 'new' || status !== 'new') ? 'border-primary shadow-[0_0_15px_rgba(0,119,187,0.5)]' : 'border-white/20'">
                                <div class="w-3 h-3 rounded-full transition-all duration-500"
                                     :class="(status === 'new' || status !== 'new') ? 'bg-primary' : 'bg-transparent'"></div>
                            </div>
                            <h3 class="mt-4 text-[12px] md:text-sm font-medium tracking-widest uppercase text-center transition-colors"
                                :class="(status === 'new' || status !== 'new') ? 'text-white' : 'text-gray-500'">Đã tiếp nhận</h3>
                        </div>
                        
                        <!-- Step 4: Payment -->
                        <div class="relative z-10 flex flex-col items-center w-28 group transition-all duration-500"
                             :class="(paymentStatus === 'paid') ? 'opacity-100' : 'opacity-50'">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center border-[2px] bg-[#040810] transition-all duration-500"
                                 :class="(paymentStatus === 'paid') ? 'border-primary shadow-[0_0_15px_rgba(0,119,187,0.5)] bg-primary' : 'border-white/20'">
                                <svg x-show="paymentStatus === 'paid'" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                <div x-show="paymentStatus !== 'paid'" class="w-3 h-3 rounded-full bg-transparent"></div>
                            </div>
                            <h3 class="mt-4 text-[12px] md:text-sm font-medium tracking-widest uppercase text-center transition-colors"
                                :class="(paymentStatus === 'paid') ? 'text-primary' : 'text-gray-500'">Thanh toán</h3>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Dynamic Status Subtext -->
                <div class="text-center mt-8 h-6">
                    <p x-show="status === 'new' && paymentStatus !== 'paid'" class="text-gray-400 text-sm tracking-wide animate-pulse">Bếp đang chuẩn bị món ăn của bạn...</p>
                    <p x-show="status === 'ready' && paymentStatus !== 'paid'" class="text-gray-400 text-sm tracking-wide animate-pulse">Nhân viên phục vụ đang mang món ra bàn...</p>
                    <p x-show="status === 'served' && paymentStatus !== 'paid'" class="text-gray-400 text-sm tracking-wide">Chúc quý khách ngon miệng! Đang chờ thanh toán.</p>
                    <p x-show="paymentStatus === 'paid'" class="text-green-400 text-sm tracking-wide font-medium">Đã thanh toán thành công.</p>
                </div>
            </div>

            <!-- Order Items List as ONE Dropdown -->
            <div x-data="{ showItems: false }" class="bg-[#040810] border border-white/5 rounded-xl mb-12 shadow-2xl overflow-hidden">
                <!-- Dropdown Header -->
                <div @click="showItems = !showItems" class="p-6 md:p-8 cursor-pointer flex justify-between items-center bg-[#0d1114] border-b border-white/5 hover:bg-white/5 transition-colors">
                    <h2 class="text-white text-lg font-medium tracking-wider">Chi tiết các món đã gọi</h2>
                    <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center border border-white/10">
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="showItems ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                
                <!-- Dropdown Body -->
                <div x-show="showItems" x-collapse.duration.400ms>
                    <div class="p-6 md:p-8">
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

                        <div class="max-h-[50vh] overflow-y-auto timeline-scroll pr-2 space-y-3">
                            @foreach($order->items as $item)
                                <div x-show="items[{{ $item->id }}].status !== 'cancelled'" class="bg-[#0d1114] border border-white/5 rounded-lg overflow-hidden shadow-md p-3 relative transition-opacity duration-300">
                                    <div class="flex gap-3 items-center">
                                        <div class="w-12 h-12 rounded-md bg-gray-800 shrink-0 overflow-hidden border border-white/10 relative">
                                            <img src="{{ $item->food->image ?? '/images/default-food.jpg' }}" alt="{{ $item->food->name }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex-grow w-full">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h3 class="text-white text-sm tracking-wide font-medium">
                                                        {{ $item->food->name }}
                                                    </h3>
                                                    <p class="text-gray-400 text-[11px] mt-0.5">
                                                        SL: <span x-text="items[{{ $item->id }}].quantity"></span> 
                                                        <span class="mx-1">•</span> {{ number_format($item->unit_price * 1000, 0, ',', '.') }} đ
                                                    </p>
                                                </div>
                                                <div class="text-right flex flex-col items-end">
                                                    <span class="text-primary font-medium text-sm" x-text="(items[{{ $item->id }}].quantity * {{ $item->unit_price * 1000 }}).toLocaleString('vi-VN') + ' đ'"></span>
                                                    
                                                    <!-- Actions (Cancel/Reduce) when 'new' -->
                                                    <div class="mt-2" x-show="items[{{ $item->id }}].status === 'new'">
                                                        <form action="{{ route('order.item.update_quantity', ['order' => $order->id, 'item' => $item->id]) }}" method="POST" class="flex items-center gap-2" x-data="{ qty: {{ $item->quantity }}, maxQty: {{ $item->quantity }} }">
                                                            @csrf
                                                            <div class="flex items-center bg-[#040810] rounded border border-white/20 h-6">
                                                                <button type="button" @click="if(qty > 0) qty--" class="w-6 h-full flex items-center justify-center text-white hover:bg-gray-800 rounded-l leading-none pb-0.5">-</button>
                                                                <input type="number" name="quantity" x-model="qty" readonly class="w-6 h-full bg-transparent border-none text-white text-center text-xs p-0 focus:ring-0 leading-none pointer-events-none">
                                                                <button type="button" @click="if(qty < maxQty) qty++" class="w-6 h-full flex items-center justify-center text-white hover:bg-gray-800 rounded-r" :class="qty >= maxQty ? 'opacity-50 cursor-not-allowed' : ''">+</button>
                                                            </div>
                                                            <button type="submit" x-show="qty < maxQty" class="px-2 py-1 bg-primary text-white text-[9px] uppercase font-bold tracking-wider rounded shadow hover:bg-blue-600 transition-colors">
                                                                Xác nhận
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Progress Bar for individual item -->
                                    <div class="mt-3">
                                        <div class="flex justify-between items-end mb-1">
                                            <span class="text-[9px] uppercase tracking-wider font-semibold"
                                                  :class="['ready','served','completed'].includes(items[{{ $item->id }}].status) ? 'text-green-400' : 'text-blue-400'"
                                                  x-text="getItemStatusText(items[{{ $item->id }}].status)">
                                            </span>
                                            <span class="text-[9px] text-gray-500">
                                                <span x-text="Math.floor(getItemProgress({{ $item->id }}))"></span>%
                                            </span>
                                        </div>
                                        <div class="w-full h-1 bg-gray-800 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-1000 ease-linear relative"
                                                 :class="['ready','served','completed'].includes(items[{{ $item->id }}].status) ? 'bg-green-500' : 'bg-primary'"
                                                 :style="{ width: getItemProgress({{ $item->id }}) + '%' }">
                                                <div x-show="['new','preparing'].includes(items[{{ $item->id }}].status)" class="absolute inset-0 bg-white/20 animate-pulse"></div>     
                                            </div>
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
                timerInterval: null,
                currentTime: new Date().getTime(),
                
                items: {
                    @foreach($order->items as $item)
                        {{ $item->id }}: {
                            status: '{{ $item->status }}',
                            quantity: {{ $item->quantity }},
                            prepMins: {{ $item->food->preparation_time ? ($item->food->preparation_time * $item->quantity) : 5 }},
                            createdAtMs: {{ $item->created_at->timestamp * 1000 }},
                            updatedAtMs: {{ $item->updated_at->timestamp * 1000 }}
                        },
                    @endforeach
                },

                initTracker() {
                    // Start progress bar tick
                    this.timerInterval = setInterval(() => {
                        this.currentTime = new Date().getTime();
                    }, 1000);

                    // Fetch status from API without replacing DOM
                    this.pollInterval = setInterval(() => {
                        this.fetchStatus();
                    }, 3000); // Fetch every 3 seconds
                },

                getProgressWidth() {
                    if (this.paymentStatus === 'paid') return '100%';
                    return '0%';
                },

                getItemStatusText(status) {
                    switch(status) {
                        case 'new': return 'Đang đợi bếp';
                        case 'preparing': return 'Đang nấu';
                        case 'ready': return 'Nấu xong';
                        case 'served': return 'Đã phục vụ';
                        case 'completed': return 'Đã hoàn tất';
                        case 'cancelled': return 'Đã huỷ';
                        default: return status;
                    }
                },

                getItemProgress(id) {
                    const item = this.items[id];
                    if (!item) return 0;
                    if (['ready', 'served', 'completed'].includes(item.status)) return 100;
                    if (['cancelled', 'new'].includes(item.status)) return 0;
                    
                    const elapsed = Math.floor((this.currentTime - item.updatedAtMs) / 1000);
                    const total = item.prepMins * 60;
                    let p = (elapsed / total) * 100;
                    
                    if (p > 95 && item.status !== 'ready') {
                        p = 95; // Stop at 95% until Chef marks it ready
                    }
                    
                    return Math.min(100, Math.max(0, p));
                },

                fetchStatus() {
                    fetch(`/api/order/${this.orderId}/status`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(res => res.json())
                        .then(data => {
                            this.status = data.status;
                            this.paymentStatus = data.payment_status;
                            this.allItemsServed = data.all_items_served;
                            
                            // Dynamically update items' status without reloading DOM
                            data.items.forEach(apiItem => {
                                if (this.items[apiItem.id]) {
                                    this.items[apiItem.id].status = apiItem.status;
                                    this.items[apiItem.id].quantity = apiItem.quantity;
                                    if (apiItem.updatedAtMs) {
                                        this.items[apiItem.id].updatedAtMs = apiItem.updatedAtMs;
                                    }
                                }
                            });
                            
                            if (this.status === 'completed' && this.paymentStatus === 'paid') {
                                clearInterval(this.pollInterval);
                                clearInterval(this.timerInterval);
                            }
                        })
                        .catch(err => console.error('Error fetching status:', err));
                }
            }));
        });
    </script>
    @endpush
</x-layouts.app>
