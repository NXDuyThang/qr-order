<div class="flex flex-col gap-2">
    @foreach ($getRecord()->items->where('status', '!=', 'cancelled') as $item)
        <div class="flex items-center justify-between text-sm p-2 rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
            <div class="flex items-center gap-2 flex-1 flex-wrap">
                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $item->food->name }}</span>
                <span class="text-gray-500 dark:text-gray-400">x{{ $item->quantity }}</span>
                
                @if($item->food->preparation_time)
                    <span class="text-xs text-gray-400" title="Thời gian chuẩn bị">(⏳ {{ $item->food->preparation_time * $item->quantity }}p)</span>
                @endif
                
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                    @if($item->status === 'new') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                    @elseif($item->status === 'preparing') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                    @elseif($item->status === 'ready') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                    @elseif($item->status === 'served') bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400
                    @elseif($item->status === 'completed') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                    @else bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400 @endif
                ">
                    @switch($item->status)
                        @case('new') Mới đặt @break
                        @case('preparing') Đang làm @break
                        @case('ready') Nấu xong @break
                        @case('served') Đã giao @break
                        @case('completed') Hoàn tất @break
                        @default {{ $item->status }}
                    @endswitch
                </span>
            </div>
            
            <div class="flex items-center gap-1 ml-2">
                @if($item->status === 'new' && (auth()->user()->is_admin || in_array(auth()->user()->role, ['chef', 'admin', 'manager'])))
                    <x-filament::button color="warning" size="xs" wire:click="updateItemStatus({{ $item->id }}, 'preparing')">
                        Nấu
                    </x-filament::button>
                @endif
                
                @if($item->status === 'preparing' && (auth()->user()->is_admin || in_array(auth()->user()->role, ['chef', 'admin', 'manager'])))
                    <x-filament::button color="info" size="xs" wire:click="updateItemStatus({{ $item->id }}, 'ready')">
                        Xong
                    </x-filament::button>
                @endif
                
                @if($item->status === 'ready' && (auth()->user()->is_admin || in_array(auth()->user()->role, ['waiter', 'admin', 'manager'])))
                    <x-filament::button color="success" size="xs" wire:click="updateItemStatus({{ $item->id }}, 'served')">
                        Phục vụ
                    </x-filament::button>
                @endif
            </div>
        </div>
    @endforeach
</div>
