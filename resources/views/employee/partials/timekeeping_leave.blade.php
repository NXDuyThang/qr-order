<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 flex-grow">
    <!-- Cột Điểm Danh -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 flex flex-col h-full">
        <h2 class="text-xl font-bold mb-4 flex items-center gap-2 border-b dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-100">
            <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Điểm Danh (Hôm nay: {{ now()->format('d/m/Y') }})
        </h2>

        @if(!$todayRecord)
            <div class="text-center py-6 flex-grow flex flex-col justify-center">
                @if(now()->format('H:i') > '09:00')
                    <p class="text-red-500 font-bold mb-4">Đã quá giờ điểm danh (09:00). Bạn được ghi nhận là không đi làm hôm nay.</p>
                @else
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Bạn chưa điểm danh vào (Check-in).</p>
                    <form action="{{ route('employee.check_in') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded shadow-lg transition-transform transform active:scale-95">
                            Bấm vào đây để Check-in
                        </button>
                    </form>
                @endif
            </div>
        @else
            <div class="space-y-4 flex-grow flex flex-col justify-center">
                <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-3 rounded">
                    <span class="text-gray-600 dark:text-gray-300">Giờ vào:</span>
                    <span class="font-bold text-gray-800 dark:text-gray-100">{{ $todayRecord->check_in?->format('H:i:s') }}</span>
                </div>
                <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-3 rounded">
                    <span class="text-gray-600 dark:text-gray-300">Trạng thái:</span>
                    @if($todayRecord->status === 'present')
                        <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-sm font-bold rounded">Đúng giờ</span>
                    @elseif($todayRecord->status === 'late')
                        <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 text-sm font-bold rounded">Đi trễ</span>
                    @elseif($todayRecord->status === 'absent')
                        <span class="px-2 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 text-sm font-bold rounded">Không đi làm</span>
                    @elseif($todayRecord->status === 'early_leave')
                        <span class="px-2 py-1 bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 text-sm font-bold rounded">Về sớm</span>
                    @elseif($todayRecord->status === 'late_early')
                        <span class="px-2 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 text-sm font-bold rounded">Đi trễ & Về sớm</span>
                    @else
                        <span class="text-gray-600 dark:text-gray-300">{{ $todayRecord->status }}</span>
                    @endif
                </div>
                
                <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-3 rounded">
                    <span class="text-gray-600 dark:text-gray-300">Giờ ra:</span>
                    @if($todayRecord->check_out)
                        <span class="font-bold text-gray-800 dark:text-gray-100">{{ $todayRecord->check_out->format('H:i:s') }}</span>
                    @else
                        <span class="text-red-500 dark:text-red-400 italic">Chưa check-out</span>
                    @endif
                </div>

                @if($todayRecord->check_out && $todayRecord->check_in)
                    @php
                        $hours = \Carbon\Carbon::parse($todayRecord->check_in)->diffInMinutes($todayRecord->check_out) / 60;
                        if (\Carbon\Carbon::parse($todayRecord->check_out)->format('H:i') >= '13:00') {
                            $hours -= 1; // Nghỉ trưa 1 tiếng
                        }
                        $hours = max(0, round($hours, 2));
                    @endphp
                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-3 rounded border border-blue-200 dark:border-blue-800">
                        <span class="text-gray-600 dark:text-gray-300 font-semibold">Thời gian làm việc:</span>
                        <span class="font-bold text-blue-600 dark:text-blue-400">{{ $hours }} giờ</span>
                    </div>
                @elseif(!$todayRecord->check_out && $todayRecord->check_in && $todayRecord->status !== 'absent')
                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-3 rounded border border-blue-200 dark:border-blue-800">
                        <span class="text-gray-600 dark:text-gray-300 font-semibold">Thời gian làm việc hiện tại:</span>
                        <span id="live-working-time" class="font-bold text-blue-600 dark:text-blue-400 tracking-wider">00:00:00</span>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const checkInTime = new Date('{{ $todayRecord->check_in->toIso8601String() }}').getTime();
                            const timerElement = document.getElementById('live-working-time');
                            
                            function updateTimer() {
                                const now = new Date().getTime();
                                let diff = Math.floor((now - checkInTime) / 1000);
                                
                                if (diff < 0) diff = 0;

                                const hours = Math.floor(diff / 3600);
                                const minutes = Math.floor((diff % 3600) / 60);
                                const seconds = diff % 60;

                                timerElement.innerText = 
                                    String(hours).padStart(2, '0') + ':' + 
                                    String(minutes).padStart(2, '0') + ':' + 
                                    String(seconds).padStart(2, '0');
                            }

                            if (timerElement && checkInTime) {
                                setInterval(updateTimer, 1000);
                                updateTimer();
                            }
                        });
                    </script>
                @endif

                @if(!$todayRecord->check_out && $todayRecord->status !== 'absent')
                    <form action="{{ route('employee.check_out') }}" method="POST" class="mt-4 mt-auto">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded shadow-lg transition-transform transform active:scale-95">
                            Kết thúc ca làm (Check-out)
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>

    <!-- Cột Xin Nghỉ Phép -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 flex flex-col h-full">
        <h2 class="text-xl font-bold mb-4 flex items-center gap-2 border-b dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-100">
            <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Xin Nghỉ Phép
        </h2>

        <form action="{{ route('employee.leave_request') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Ngày bắt đầu nghỉ</label>
                <!-- Ngày bắt đầu tối thiểu sau 48h -->
                <input type="date" name="start_date" required min="{{ now()->addDays(2)->format('Y-m-d') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">* Phải báo trước ít nhất 48h.</p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Ngày kết thúc nghỉ</label>
                <input type="date" name="end_date" required min="{{ now()->addDays(2)->format('Y-m-d') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Lý do</label>
                <textarea name="reason" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Lý do xin nghỉ..."></textarea>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Gửi Yêu Cầu
            </button>
        </form>
    </div>
</div>
