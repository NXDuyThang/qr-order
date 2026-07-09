<x-filament-panels::page>
    <div class="space-y-6">
        
        <!-- Bộ lọc tháng/năm -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
            <form wire:submit.prevent="$refresh" class="flex items-end gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tháng</label>
                    <select wire:model="month" class="block w-32 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}">Tháng {{ $m }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Năm</label>
                    <select wire:model="year" class="block w-32 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        @for($y = now()->year - 2; $y <= now()->year; $y++)
                            <option value="{{ $y }}">Năm {{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="bg-primary-600 hover:bg-primary-500 text-white font-medium py-2 px-4 rounded-lg shadow text-sm transition">
                    Lọc dữ liệu
                </button>
            </form>
        </div>

        <!-- Bảng tính lương -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-900">Nhân viên</th>
                            <th class="px-6 py-4 font-semibold text-gray-900">Vai trò</th>
                            <th class="px-6 py-4 font-semibold text-gray-900 text-right">Lương cơ bản</th>
                            <th class="px-6 py-4 font-semibold text-gray-900 text-center">Ngày phép (đã duyệt)</th>
                            <th class="px-6 py-4 font-semibold text-gray-900 text-right">Tiền trừ (Phép)</th>
                            <th class="px-6 py-4 font-semibold text-gray-900 text-right">Lương thực nhận</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php
                            $payrollData = $this->getPayrollData();
                        @endphp
                        
                        @forelse($payrollData as $row)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="font-medium text-gray-900">{{ $row['user']->name }}</div>
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $row['user']->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($row['user']->role === 'manager')
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-800">Quản lý</span>
                                    @elseif($row['user']->role === 'chef')
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-orange-100 text-orange-800">Đầu bếp</span>
                                    @elseif($row['user']->role === 'waiter')
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">Phục vụ</span>
                                    @else
                                        {{ $row['user']->role }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-medium text-gray-700">
                                    {{ number_format($row['base_salary'], 0, ',', '.') }} VNĐ
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($row['leave_days'] > 0)
                                        <span class="text-red-600 font-bold">{{ $row['leave_days'] }}</span>
                                    @else
                                        <span class="text-gray-400">0</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-red-600">
                                    - {{ number_format($row['deduction'], 0, ',', '.') }} VNĐ
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-green-600 text-base">
                                    {{ number_format($row['net_salary'], 0, ',', '.') }} VNĐ
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    Không có dữ liệu nhân viên.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-50 p-4 border-t border-gray-200 text-xs text-gray-500 italic">
                * Công thức: Tiền trừ = (Lương cơ bản / 26) x Số ngày nghỉ phép đã duyệt trong tháng. <br>
                * Bảng tính này chỉ liệt kê các đơn nghỉ phép có trạng thái Đã duyệt (Approved).
            </div>
        </div>

    </div>
</x-filament-panels::page>
