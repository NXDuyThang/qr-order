<x-filament-panels::page>
    <div class="grid gap-6">
        <x-filament::section>
            <h2 class="text-lg font-bold mb-4">Điểm danh ngày hôm nay: {{ now()->format('d/m/Y') }}</h2>
            
            @if(!$todayRecord)
                @if(now()->format('H:i') > '09:00')
                    <p class="mb-4 text-danger-600 font-bold">Đã quá giờ điểm danh (09:00). Bạn được ghi nhận là không đi làm hôm nay.</p>
                @else
                    <p class="mb-4">Bạn chưa điểm danh vào (Check-in).</p>
                    <x-filament::button wire:click="checkIn" color="primary">
                        Check-in
                    </x-filament::button>
                @endif
            @else
                <div class="mb-4 space-y-2">
                    <p><strong>Giờ vào:</strong> {{ $todayRecord->check_in?->format('H:i:s') }}</p>
                    <p><strong>Giờ ra:</strong> {{ $todayRecord->check_out ? $todayRecord->check_out->format('H:i:s') : 'Chưa Check-out' }}</p>
                    
                    @if($todayRecord->check_out && $todayRecord->check_in)
                        @php
                            $hours = \Carbon\Carbon::parse($todayRecord->check_in)->diffInMinutes($todayRecord->check_out) / 60;
                            if (\Carbon\Carbon::parse($todayRecord->check_out)->format('H:i') >= '13:00') {
                                $hours -= 1;
                            }
                            $hours = max(0, round($hours, 2));
                        @endphp
                        <p><strong>Thời gian làm việc:</strong> <span class="text-primary-600 font-bold">{{ $hours }} giờ</span></p>
                    @endif

                    <p><strong>Trạng thái:</strong> 
                        @if($todayRecord->status === 'present')
                            <span class="text-success-600 font-bold">Đúng giờ</span>
                        @elseif($todayRecord->status === 'late')
                            <span class="text-warning-600 font-bold">Đi trễ</span>
                        @elseif($todayRecord->status === 'absent')
                            <span class="text-danger-600 font-bold">Không đi làm</span>
                        @elseif($todayRecord->status === 'early_leave')
                            <span class="text-warning-600 font-bold">Về sớm</span>
                        @elseif($todayRecord->status === 'late_early')
                            <span class="text-danger-600 font-bold">Đi trễ & Về sớm</span>
                        @else
                            <span class="text-gray-600">{{ $todayRecord->status }}</span>
                        @endif
                    </p>
                </div>

                @if(!$todayRecord->check_out && $todayRecord->status !== 'absent')
                    <x-filament::button wire:click="checkOut" color="success">
                        Check-out
                    </x-filament::button>
                @endif
            @endif
        </x-filament::section>
    </div>
</x-filament-panels::page>
