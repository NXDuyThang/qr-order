<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Hệ thống QR Order</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #0d1114;
            color: #ffffff;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .pos-header {
            background-color: #1a222c;
            border-bottom: 1px solid #333a48;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .table-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1.5rem;
            padding: 2rem;
        }
        .pos-table-card {
            background-color: #1a222c;
            border: 2px solid #333a48;
            border-radius: 12px;
            padding: 2rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            aspect-ratio: 1;
        }
        .pos-table-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }
        /* States */
        .pos-table-card.available {
            border-color: #22c55e;
            background: linear-gradient(145deg, #163321, #1a222c);
        }
        .pos-table-card.available:hover {
            box-shadow: 0 10px 25px rgba(34, 197, 94, 0.2);
        }
        .pos-table-card.occupied {
            border-color: #ef4444;
            background: linear-gradient(145deg, #3f1a1a, #1a222c);
        }
        .pos-table-card.occupied:hover {
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.2);
        }
        .table-name {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #ffffff;
        }
        .table-status {
            font-size: 0.9rem;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.05em;
        }
        .text-available { color: #4ade80; }
        .text-occupied { color: #f87171; }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.85);
            backdrop-filter: blur(5px);
            align-items: center;
            justify-content: center;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background-color: #1a222c;
            border: 1px solid #333a48;
            padding: 2.5rem;
            border-radius: 16px;
            width: 90%;
            max-width: 550px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        /* Styling for inputs to be large and touch-friendly */
        .pos-input {
            width: 100%;
            background-color: #0d1114;
            border: 1px solid #333a48;
            border-radius: 8px;
            padding: 1rem 1.25rem;
            color: white;
            font-size: 1.1rem;
            transition: border-color 0.2s;
        }
        .pos-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>
<body>

    <!-- Topbar -->
    <header class="pos-header">
        <div class="flex items-center gap-4">
            <h1 class="text-2xl font-bold text-blue-500 flex items-center gap-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                POS System
            </h1>
            <span class="text-gray-400 border-l border-gray-700 pl-4 ml-2" id="clock">--:--:--</span>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="text-right hidden md:block">
                <p class="text-sm text-gray-400">Nhân viên trực</p>
                <p class="font-semibold">{{ auth()->check() ? auth()->user()->name : 'Guest' }}</p>
            </div>
            <a href="{{ url('/admin') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-gray-300 transition" title="Trang chủ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="hidden sm:inline">Trang chủ</span>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        @if(session('success'))
            <div class="m-6 p-4 bg-green-900/50 border border-green-500 text-green-200 rounded-lg flex items-center gap-3">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="m-6 p-4 bg-red-900/50 border border-red-500 text-red-200 rounded-lg flex items-center gap-3">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="table-grid">
            @foreach($tables as $table)
                <div class="pos-table-card {{ $table->status }}" onclick="handleTableClick({{ $table->id }}, '{{ $table->status }}', '{{ $table->name }}')">
                    <h3 class="table-name">{{ $table->name }}</h3>
                    
                    @if($table->status === 'available')
                        <div class="mt-2 w-12 h-1 bg-green-500 rounded-full mx-auto mb-3"></div>
                        <span class="table-status text-available flex items-center gap-1 justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Trống
                        </span>
                    @elseif($table->status === 'occupied')
                        <div class="mt-2 w-12 h-1 bg-red-500 rounded-full mx-auto mb-3"></div>
                        <span class="table-status text-occupied flex items-center gap-1 justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Có khách
                        </span>
                    @else
                        <div class="mt-2 w-12 h-1 bg-yellow-500 rounded-full mx-auto mb-3"></div>
                        <span class="table-status text-yellow-500">Đã đặt</span>
                    @endif
                </div>
            @endforeach
        </div>
    </main>

    <!-- Modal Tạo Order -->
    <div id="orderModal" class="modal">
        <div class="modal-content relative">
            <button onclick="closeModal()" class="absolute top-5 right-5 text-gray-500 hover:text-white transition bg-gray-800 hover:bg-gray-700 rounded-full p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-white mb-2" id="modalTitle">Tạo Order Mới</h2>
                <p class="text-gray-400">Nhập thông tin khách hàng để mở bàn</p>
            </div>
            
            <form id="orderForm" method="POST" action="">
                @csrf
                <div class="mb-5">
                    <label class="block text-gray-400 mb-2 font-medium">Số điện thoại <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone" id="phoneInput" required class="pos-input" list="phoneList" autocomplete="off" oninput="handlePhoneInput()" placeholder="Nhập số SĐT...">
                    <datalist id="phoneList">
                        @foreach($users as $user)
                            <option value="{{ $user->phone }}">{{ $user->name }}</option>
                        @endforeach
                    </datalist>
                </div>
                
                <div class="mb-8">
                    <label class="block text-gray-400 mb-2 font-medium">Tên khách hàng</label>
                    <input type="text" name="name" id="nameInput" class="pos-input" list="nameList" autocomplete="off" oninput="handleNameInput()" placeholder="Tên khách sẽ tự động điền nếu đã có">
                    <datalist id="nameList">
                        @foreach($users as $user)
                            <option value="{{ $user->name }}">{{ $user->phone }}</option>
                        @endforeach
                    </datalist>
                </div>

                <div class="flex gap-4">
                    <button type="button" onclick="closeModal()" class="flex-1 py-4 bg-gray-800 text-white font-bold text-lg rounded-xl hover:bg-gray-700 transition">Hủy bỏ</button>
                    <button type="submit" class="flex-1 py-4 bg-blue-600 text-white font-bold text-lg rounded-xl hover:bg-blue-500 transition shadow-lg shadow-blue-900/50 flex justify-center items-center gap-2">
                        Tiếp tục Đặt món
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const usersData = @json($users);

        // Update clock
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleTimeString('vi-VN');
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Handle table click
        function handleTableClick(tableId, status, tableName) {
            if (status === 'occupied') {
                window.location.href = "{{ url('/staff/pos/table') }}/" + tableId + "/order";
            } else if (status === 'available') {
                // Open modal for available table
                document.getElementById('modalTitle').innerText = 'Mở: ' + tableName;
                document.getElementById('orderForm').action = "{{ url('/staff/pos/table') }}/" + tableId + "/order";
                
                document.getElementById('phoneInput').value = '';
                document.getElementById('nameInput').value = '';
                
                document.getElementById('orderModal').classList.add('active');
                setTimeout(() => document.getElementById('phoneInput').focus(), 100);
            }
        }

        // Close modal
        function closeModal() {
            document.getElementById('orderModal').classList.remove('active');
        }

        // Close modal on outside click
        window.onclick = function(event) {
            const modal = document.getElementById('orderModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Auto-fill logic
        function handlePhoneInput() {
            const phone = document.getElementById('phoneInput').value;
            const user = usersData.find(u => u.phone === phone);
            if (user) {
                document.getElementById('nameInput').value = user.name;
            }
        }

        function handleNameInput() {
            const name = document.getElementById('nameInput').value;
            const user = usersData.find(u => u.name === name && u.phone);
            if (user) {
                document.getElementById('phoneInput').value = user.phone;
            }
        }
    </script>
</body>
</html>
