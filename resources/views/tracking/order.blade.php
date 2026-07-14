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

            <!-- Action Area -->
            <div class="text-center relative z-10 transition-all duration-500" style="display: none;" x-show="status === 'served' || status === 'completed'">
                <div x-show="paymentStatus === 'pending'" style="display: none;" x-transition.opacity>
                    @if($order->payment_method === 'transfer')
                        <p class="text-white mb-6 tracking-wide">Vui lòng quét mã QR để thanh toán.</p>
                        <a href="{{ route('checkout.transfer', ['order' => $order->id]) }}" class="inline-block bg-primary text-white px-10 py-4 text-[13px] font-semibold tracking-[0.2em] uppercase hover:bg-white hover:text-primary transition-colors shadow-[0_0_15px_rgba(0,119,187,0.3)] hover:shadow-[0_0_20px_rgba(255,255,255,0.4)]">
                            Mã QR Thanh Toán
                        </a>
                    @else
                        <p class="text-white mb-6 tracking-wide">Vui lòng thanh toán bằng tiền mặt cho nhân viên.</p>
                        <button disabled class="inline-block bg-gray-800 text-gray-400 border border-gray-600 px-10 py-4 text-[13px] font-semibold tracking-[0.2em] uppercase cursor-not-allowed">
                            Chờ Thu Ngân
                        </button>
                    @endif
                </div>

                <div x-show="paymentStatus === 'paid'" style="display: none;" x-transition.opacity>
                    <div class="bg-green-900/20 border border-green-500/30 text-green-400 p-6 rounded-xl inline-block">
                        <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-lg tracking-widest uppercase">Cảm ơn quý khách</p>
                        <p class="text-sm mt-2 text-green-500/70">Thanh toán đã được xác nhận.</p>
                    </div>
                    <div class="mt-8">
                        <a href="{{ route('welcome') }}" class="text-gray-400 hover:text-white transition-colors text-sm border-b border-transparent hover:border-white pb-1">Trở về trang chủ</a>
                    </div>
                </div>
            </div>

            <!-- Back to Menu (if still waiting) -->
            <div class="text-center mt-12" x-show="status !== 'served' && status !== 'completed'">
                <a href="{{ route('order_at_table', ['table_id' => $order->table_id]) }}" class="text-gray-500 hover:text-white transition-colors text-sm border-b border-transparent hover:border-white pb-1">
                    Trở về danh mục đặt món
                </a>
            </div>
            
        </div>
    </div>

    @push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderTracker', () => ({
                status: '{{ $order->status }}',
                paymentStatus: '{{ $order->payment_status }}',
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
