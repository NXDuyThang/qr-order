<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Order - Bàn {{ $table->name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #0d1114;
            color: #ffffff;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Prevent body scroll, layout handles scrolling */
            height: 100vh;
        }
        /* Custom Scrollbar for dark theme */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #1a222c; }
        ::-webkit-scrollbar-thumb { background: #333a48; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #4b5563; }
        
        .food-card {
            background-color: #1a222c;
            border: 1px solid #333a48;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.2s;
        }
        .food-card:active { transform: scale(0.97); }
        .food-card:hover { border-color: #3b82f6; }
        
        .status-badge {
            font-size: 0.75rem;
            padding: 0.1rem 0.5rem;
            border-radius: 9999px;
            font-weight: 600;
        }
        .status-new { background-color: #374151; color: #d1d5db; }
        .status-preparing { background-color: #78350f; color: #fcd34d; }
        .status-ready { background-color: #064e3b; color: #34d399; }
        .status-served { background-color: #1e3a8a; color: #93c5fd; }
    </style>
</head>
<body x-data="posOrder()">
    
    <div class="flex h-screen w-full">
        <!-- LEFT PANEL: MENU -->
        <div class="flex flex-col bg-[#0d1114] border-r border-gray-800" style="width: 70%; height: 100vh; overflow: hidden;">
            <!-- Topbar Left -->
            <div class="p-4 bg-[#1a222c] border-b border-gray-800 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('pos.index') }}" class="p-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-gray-300 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <h1 class="text-xl font-bold text-white">Menu</h1>
                </div>
                <!-- Categories -->
                <div class="flex gap-2 overflow-x-auto hide-scrollbar max-w-lg">
                    <button @click="currentCategory = 'all'" :class="currentCategory === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-300'" class="px-4 py-2 rounded-full whitespace-nowrap font-medium text-sm transition">Tất cả</button>
                    @foreach($categories as $cat)
                        <button @click="currentCategory = {{ $cat->id }}" :class="currentCategory === {{ $cat->id }} ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-300'" class="px-4 py-2 rounded-full whitespace-nowrap font-medium text-sm transition">{{ $cat->name }}</button>
                    @endforeach
                </div>
            </div>

            <!-- Food Grid -->
            <div class="p-4" style="flex: 1 1 auto; overflow-y: auto; height: 100%;">
                <div class="grid grid-cols-3 xl:grid-cols-4 gap-4 pb-32">
                    @foreach($categories as $cat)
                        @foreach($cat->food as $food)
                            <div class="food-card flex flex-col" x-show="currentCategory === 'all' || currentCategory === {{ $cat->id }}" @click="addToCart({{ $food->id }}, '{{ addslashes($food->name) }}', {{ $food->price }})">
                                <div class="h-32 bg-gray-800 overflow-hidden relative">
                                    <img src="{{ str_starts_with($food->image, 'http') || str_starts_with($food->image, '/images/') ? $food->image : asset('storage/'.$food->image) }}" alt="{{ $food->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="p-3 flex-1 flex flex-col justify-between">
                                    <h3 class="text-sm font-semibold text-white leading-tight mb-2">{{ $food->name }}</h3>
                                    <p class="text-blue-400 font-bold">{{ number_format($food->price * 1000, 0, ',', '.') }}đ</p>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL: BILL -->
        <div class="bg-[#111827] flex flex-col shadow-2xl relative" style="width: 30%; height: 100vh; overflow: hidden;">
            <!-- Topbar Right -->
            <div class="p-4 bg-[#1a222c] border-b border-gray-800 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ $table->name }}</h2>
                    <div class="flex flex-col mt-1">
                        <p class="text-gray-400 text-sm whitespace-nowrap">Phục vụ: <span class="text-white">{{ Auth::user()->name }}</span></p>
                        @if($activeOrder && $activeOrder->user)
                            <p class="text-gray-400 text-sm whitespace-nowrap">Khách: <span class="text-white">{{ $activeOrder->user->name }}</span></p>
                        @endif
                    </div>
                </div>
                @if($activeOrder)
                    <span class="px-3 py-1 bg-yellow-900/50 text-yellow-400 border border-yellow-700 rounded-full text-xs font-bold uppercase">Mở</span>
                @else
                    <span class="px-3 py-1 bg-gray-800 text-gray-400 rounded-full text-xs font-bold uppercase">Bàn Trống</span>
                @endif
            </div>

            <!-- Bill Content -->
            <div id="bill-content" class="p-4 flex flex-col gap-4" style="flex: 1 1 auto; overflow-y: auto; height: 100%;">
                
                <!-- 1. Existing Items -->
                @if($activeOrder && $activeOrder->items->count() > 0)
                    <div>
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider border-b border-gray-800 pb-2 mb-3">Đã gọi</h3>
                        <div class="flex flex-col gap-3">
                            @foreach($activeOrder->items as $item)
                                @if($item->status !== 'cancelled')
                                    <div class="bg-gray-800/50 p-3 rounded-lg border border-gray-700 flex flex-col gap-2" id="item-{{ $item->id }}">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <p class="text-white font-medium">{{ $item->food->name ?? 'Món đã xóa' }} <span class="text-gray-400 text-sm">x{{ $item->quantity }}</span></p>
                                                <p class="text-gray-400 text-sm">{{ number_format((($item->food->price ?? 0) * 1000) * $item->quantity, 0, ',', '.') }}đ</p>
                                            </div>
                                            <!-- Status -->
                                            <div>
                                                @if($item->status === 'new') <span class="status-badge status-new">Mới đặt</span>
                                                @elseif($item->status === 'preparing') <span class="status-badge status-preparing">Đang làm</span>
                                                @elseif($item->status === 'ready') <span class="status-badge status-ready">Nấu xong</span>
                                                @elseif($item->status === 'served') <span class="status-badge status-served">Đã giao</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Actions -->
                                        @if($item->status === 'ready')
                                            <button onclick="serveItem({{ $item->id }})" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded mt-1 transition">
                                                Xác nhận Đã Mang Lên
                                            </button>
                                        @elseif(in_array($item->status, ['new', 'preparing']))
                                            @if($item->quantity > 1)
                                                <div class="flex gap-2 w-full mt-1">
                                                    <form action="{{ route('order.item.reduce', ['order' => $activeOrder->id, 'item' => $item->id]) }}" method="POST" class="flex-1">
                                                        @csrf
                                                        <button type="submit" class="w-full py-2 bg-orange-600 hover:bg-orange-500 text-white text-sm font-bold rounded transition">
                                                            -1 phần
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('order.item.cancel', ['order' => $activeOrder->id, 'item' => $item->id]) }}" method="POST" class="flex-1">
                                                        @csrf
                                                        <button type="submit" class="w-full py-2 bg-red-600 hover:bg-red-500 text-white text-sm font-bold rounded transition">
                                                            Hủy toàn bộ
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <form action="{{ route('order.item.cancel', ['order' => $activeOrder->id, 'item' => $item->id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-full py-2 bg-red-600 hover:bg-red-500 text-white text-sm font-bold rounded mt-1 transition">
                                                        Hủy món
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- 2. New Items (Cart) -->
                <div x-show="cart.length > 0">
                    <h3 class="text-sm font-bold text-blue-400 uppercase tracking-wider border-b border-gray-800 pb-2 mb-3 mt-2">Món mới (Chưa gửi bếp)</h3>
                    <div class="flex flex-col gap-2">
                        <template x-for="(item, index) in cart" :key="item.id">
                            <div class="bg-blue-900/20 p-3 rounded-lg border border-blue-900/50 flex justify-between items-center">
                                <div class="flex-1">
                                    <p class="text-white font-medium" x-text="item.name"></p>
                                    <p class="text-gray-400 text-sm" x-text="formatMoney(item.price * item.quantity) + 'đ'"></p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center bg-gray-900 rounded-lg overflow-hidden border border-gray-700">
                                        <button @click="updateQuantity(index, -1)" class="w-8 h-8 flex items-center justify-center text-gray-300 hover:bg-gray-700">-</button>
                                        <span class="w-8 text-center text-sm font-medium" x-text="item.quantity"></span>
                                        <button @click="updateQuantity(index, 1)" class="w-8 h-8 flex items-center justify-center text-gray-300 hover:bg-gray-700">+</button>
                                    </div>
                                    <button @click="removeFromCart(index)" class="text-red-400 hover:text-red-300 p-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Bottom Actions -->
            <div class="p-4 bg-[#1a222c] border-t border-gray-800">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-400 font-medium">Tổng tiền:</span>
                    <span id="pos-total" class="text-2xl font-bold text-white" x-text="formatMoney(getTotal()) + 'đ'">{{ number_format((($activeOrder ? $activeOrder->total_price : 0) * 1000), 0, ',', '.') }}đ</span>
                </div>

                <!-- Submit Cart -->
                <button x-show="cart.length > 0" @click="submitOrder()" :disabled="isSubmitting" class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold text-lg rounded-xl transition flex justify-center items-center gap-2" style="display: none;">
                    <span x-text="isSubmitting ? 'Đang gửi...' : 'GỬI BẾP'"></span>
                    <svg x-show="!isSubmitting" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>

                <!-- Payment Options -->
                <div id="pos-actions">
                @if($activeOrder && $activeOrder->items->count() > 0)
                    <div x-show="cart.length === 0" class="flex gap-2">
                        <button onclick="openPaymentModal()" class="flex-1 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-lg rounded-xl transition flex justify-center items-center gap-2">
                            THANH TOÁN
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </button>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex justify-center items-center">
        <div class="bg-[#1a222c] border border-gray-700 rounded-2xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white">Thanh Toán Hóa Đơn</h3>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <p class="text-center text-gray-400 mb-2">Tổng cần thanh toán</p>
            <p class="text-center text-3xl font-bold text-emerald-400 mb-8" x-text="formatMoney({{ $activeOrder ? $activeOrder->total_price : 0 }}) + 'đ'"></p>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                @if($activeOrder)
                    <form action="{{ route('order.update_payment_method', $activeOrder->id) }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="payment_method" value="cash">
                        <!-- Wait, if we use cash, it redirects to tracking. Let's just submit form normally -->
                        <button type="submit" class="w-full flex flex-col items-center justify-center gap-3 p-4 bg-gray-800 hover:bg-gray-700 border border-gray-600 rounded-xl transition cursor-pointer">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="font-bold text-white">Tiền Mặt</span>
                        </button>
                    </form>
                    
                    <form action="{{ route('order.update_payment_method', $activeOrder->id) }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="payment_method" value="transfer">
                        <button type="submit" class="w-full flex flex-col items-center justify-center gap-3 p-4 bg-gray-800 hover:bg-gray-700 border border-gray-600 rounded-xl transition cursor-pointer">
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            <span class="font-bold text-white">Chuyển Khoản</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Hidden Form for submission -->
    <form id="submitCartForm" method="POST" action="{{ route('order.store') }}" style="display: none;">
        @csrf
        <input type="hidden" name="table_id" value="{{ $table->id }}">
        <input type="hidden" name="items" id="cartItemsInput">
    </form>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('posOrder', () => ({
                currentCategory: 'all',
                cart: [],
                existingTotal: {{ $activeOrder ? $activeOrder->total_price : 0 }},
                isSubmitting: false,

                init() {
                    setInterval(() => {
                        if (this.cart.length === 0 && !this.isSubmitting) {
                            fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(res => res.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const newBill = doc.getElementById('bill-content');
                                if (newBill) {
                                    document.getElementById('bill-content').innerHTML = newBill.innerHTML;
                                }
                                
                                const newTotal = doc.getElementById('pos-total');
                                if (newTotal) {
                                    document.getElementById('pos-total').innerHTML = newTotal.innerHTML;
                                }
                                
                                const newActions = doc.getElementById('pos-actions');
                                if (newActions) {
                                    document.getElementById('pos-actions').innerHTML = newActions.innerHTML;
                                }
                            });
                        }
                    }, 3000);
                },

                addToCart(id, name, price) {
                    const index = this.cart.findIndex(item => item.id === id);
                    if (index >= 0) {
                        this.cart[index].quantity++;
                    } else {
                        this.cart.push({ id, name, price, quantity: 1 });
                    }
                },

                updateQuantity(index, delta) {
                    this.cart[index].quantity += delta;
                    if (this.cart[index].quantity <= 0) {
                        this.cart.splice(index, 1);
                    }
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                getTotal() {
                    const cartTotal = this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                    return this.existingTotal + cartTotal;
                },

                formatMoney(amount) {
                    return (amount * 1000).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                },

                submitOrder() {
                    if (this.cart.length === 0) return;
                    this.isSubmitting = true;
                    document.getElementById('cartItemsInput').value = JSON.stringify(this.cart);
                    document.getElementById('submitCartForm').submit();
                }
            }));
        });

        // Functions for vanilla JS interactions
        function serveItem(itemId) {
            Swal.fire({
                title: 'Xác nhận mang lên?',
                text: "Đánh dấu món này đã được giao cho khách",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#374151',
                confirmButtonText: 'Đã giao',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('/staff/pos/order-item') }}/${itemId}/serve`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            window.location.reload();
                        } else {
                            Swal.fire('Lỗi', data.message || 'Không thể cập nhật trạng thái', 'error');
                        }
                    });
                }
            });
        }

        function openPaymentModal() {
            document.getElementById('paymentModal').classList.remove('hidden');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }
    </script>
    
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Thành công!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#3b82f6'
            });
        });
    </script>
    @endif
    
    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Lỗi',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#ef4444'
            });
        });
    </script>
    @endif
</body>
</html>
