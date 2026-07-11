<x-filament-panels::page>
    <div class="space-y-6">
        @php
            $data = $this->getSalaryData();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <x-filament::section class="col-span-1">
                <x-slot name="heading">
                    Kỳ Lương
                </x-slot>
                
                <form wire:submit.prevent="$refresh" class="space-y-4">
                    {{ $this->form }}

                    <div>
                        <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white">Tháng</label>
                        <select wire:model.live="month" class="mt-1 block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-primary-500 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/20">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">Tháng {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white">Năm</label>
                        <select wire:model.live="year" class="mt-1 block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-primary-500 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/20">
                            @for($i = now()->year - 2; $i <= now()->year; $i++)
                                <option value="{{ $i }}">Năm {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </form>
            </x-filament::section>

            <x-filament::section class="col-span-1 md:col-span-3">
                <x-slot name="heading">
                    Chi tiết Lương Tháng {{ $this->month }}/{{ $this->year }}
                </x-slot>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <tbody>
                            <tr class="border-b dark:border-gray-700">
                                <td class="py-3 px-4 font-semibold text-gray-700 dark:text-gray-300 w-1/2">Lương cơ bản (26 ngày):</td>
                                <td class="py-3 px-4 text-gray-900 dark:text-gray-100">{{ number_format($data['base_salary'], 0, ',', '.') }} VNĐ</td>
                            </tr>
                            <tr class="border-b dark:border-gray-700">
                                <td class="py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Số ngày nghỉ (đã duyệt):</td>
                                <td class="py-3 px-4 text-red-600 dark:text-red-400 font-bold">{{ $data['leave_days'] }} ngày</td>
                            </tr>
                            <tr class="border-b dark:border-gray-700">
                                <td class="py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Khấu trừ lương:</td>
                                <td class="py-3 px-4 text-red-600 dark:text-red-400 font-bold">- {{ number_format($data['deduction'], 0, ',', '.') }} VNĐ</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-bold text-lg text-primary-600 dark:text-primary-400">Thực Lãnh:</td>
                                <td class="py-3 px-4 font-bold text-lg text-green-600 dark:text-green-400">{{ number_format($data['net_salary'], 0, ',', '.') }} VNĐ</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
