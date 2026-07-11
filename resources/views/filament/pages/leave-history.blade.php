<x-filament-panels::page>
    <div class="mb-2">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <svg class="w-7 h-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Lịch Sử Nghỉ Phép
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Xem lại các yêu cầu nghỉ phép của bạn.</p>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden ring-1 ring-gray-950/5 dark:ring-white/10">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 text-left text-sm uppercase font-semibold">
                    <tr>
                        <th class="py-4 px-6">Ngày Tạo</th>
                        <th class="py-4 px-6">Thời Gian Nghỉ</th>
                        <th class="py-4 px-6">Số Ngày</th>
                        <th class="py-4 px-6">Lý Do</th>
                        <th class="py-4 px-6 text-center">Trạng Thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($leaveRequests as $req)
                        @php
                            $days = \Carbon\Carbon::parse($req->start_date)->diffInDays(\Carbon\Carbon::parse($req->end_date)) + 1;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="py-4 px-6 text-sm text-gray-500 dark:text-gray-400">
                                {{ $req->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-900 dark:text-gray-100 font-medium">
                                {{ \Carbon\Carbon::parse($req->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($req->end_date)->format('d/m/Y') }}
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-900 dark:text-gray-100">
                                {{ $days }} ngày
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-600 dark:text-gray-400 truncate max-w-xs">
                                {{ $req->reason ?? '--' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($req->status == 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        Đã duyệt
                                    </span>
                                @elseif($req->status == 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                        Từ chối
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                        Chờ duyệt
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                Bạn chưa có yêu cầu nghỉ phép nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($leaveRequests->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $leaveRequests->links() }}
            </div>
        @endif
    </div>
</x-filament-panels::page>
