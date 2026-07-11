<x-filament-panels::page>
    <div class="mb-2">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <svg class="w-7 h-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Lịch Sử Điểm Danh
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Xem lại lịch sử vào làm / tan làm của bạn theo từng t    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Cột trái: Thống kê & Lịch -->
        <div class="xl:col-span-1 space-y-6">
            <!-- Bộ lọc tháng/năm -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <form method="GET" action="{{ url('/admin/timekeeping-history') }}" class="flex flex-col gap-4">
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tháng</label>
                            <select name="month" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Năm</label>
                            <select name="year" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                @for($y = now()->year - 2; $y <= now()->year; $y++)
                                    <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>Năm {{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-500 text-white font-medium py-2 px-4 rounded-lg shadow text-sm transition">
                        Xem lịch sử
                    </button>
                </form>
            </div>

            <!-- Tổng giờ làm -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center text-center">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tổng giờ làm tháng {{ request('month', now()->month) }}</h3>
                <p class="text-4xl font-bold text-primary-600 dark:text-primary-400 mt-2">{{ $totalHours }} <span class="text-lg text-gray-500 font-normal">giờ</span></p>
            </div>

            <!-- Lịch -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-md font-bold text-gray-800 dark:text-gray-100 mb-4 border-b dark:border-gray-700 pb-2">Lịch Đi Làm</h3>
                <div class="grid grid-cols-7 gap-1 text-center text-xs mb-2 text-gray-500 dark:text-gray-400 font-medium">
                    <div>T2</div><div>T3</div><div>T4</div><div>T5</div><div>T6</div><div>T7</div><div>CN</div>
                </div>
                <div class="grid grid-cols-7 gap-1 text-sm">
                    @for($i = 1; $i < $startDayOfWeek; $i++)
                        <div class="p-2"></div>
                    @endfor

                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $status = $calendarData[$day] ?? null;
                            $style = 'background-color: #f3f4f6; color: #9ca3af;'; // Default empty
                            if ($status === 'present') {
                                $style = 'background-color: #22c55e; color: white; font-weight: bold; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);';
                            } elseif (in_array($status, ['absent', 'late_early'])) {
                                $style = 'background-color: #ef4444; color: white; font-weight: bold; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);';
                            } elseif (in_array($status, ['early_leave', 'late'])) {
                                $style = 'background-color: #f97316; color: white; font-weight: bold; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);';
                            }
                        @endphp
                        <div class="aspect-square flex items-center justify-center rounded-md" style="{{ $style }}" title="{{ $status ?? 'Trống' }}">
                            {{ $day }}
                        </div>
                    @endfor
                </div>
                
                <div class="mt-5 grid grid-cols-2 gap-2 text-xs text-gray-600 dark:text-gray-300">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full" style="background-color: #22c55e;"></span> Đúng giờ</div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full" style="background-color: #f97316;"></span> Trễ / Sớm</div>
                    <div class="flex items-center gap-2 col-span-2"><span class="w-3 h-3 rounded-full" style="background-color: #ef4444;"></span> Không đi làm / Vi phạm nặng</div>
                </div>
            </div>
        </div>

        <!-- Cột phải: Bảng chi tiết -->
        <div class="xl:col-span-2">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 h-full">
                <div class="overflow-x-auto">
                    <table class="w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/5">Ngày</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/5">Giờ vào</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/5">Giờ ra</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/5">Trạng thái</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/5">Giờ làm</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($records as $record)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700 dark:text-gray-300">
                                        {{ $record->check_in ? \Carbon\Carbon::parse($record->check_in)->format('H:i') : '--:--' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700 dark:text-gray-300">
                                        {{ $record->check_out ? \Carbon\Carbon::parse($record->check_out)->format('H:i') : '--:--' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        @if($record->status === 'present')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300">Đúng giờ</span>
                                        @elseif($record->status === 'late')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-300">Đi trễ</span>
                                        @elseif($record->status === 'absent')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300">Không đi làm</span>
                                        @elseif($record->status === 'early_leave')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-300">Về sớm</span>
                                        @elseif($record->status === 'late_early')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300">Trễ & Sớm</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">{{ ucfirst($record->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700 dark:text-gray-300">
                                        @if($record->check_out && $record->check_in)
                                            @php
                                                $hours = \Carbon\Carbon::parse($record->check_in)->diffInMinutes($record->check_out) / 60;
                                                if (\Carbon\Carbon::parse($record->check_out)->format('H:i') >= '13:00') {
                                                    $hours -= 1;
                                                }
                                                $hours = max(0, round($hours, 2));
                                            @endphp
                                            <span class="font-bold text-blue-600 dark:text-blue-400">{{ $hours }} giờ</span>
                                        @else
                                            --
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Không có dữ liệu điểm danh trong tháng này.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
