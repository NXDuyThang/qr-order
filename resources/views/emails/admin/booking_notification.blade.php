<x-mail::message>
# Đơn Đặt Bàn Mới

Bạn vừa nhận được một đơn đặt bàn mới trên hệ thống. Dưới đây là thông tin chi tiết:

- **Họ tên khách hàng:** {{ $booking->name }}
- **Số điện thoại:** {{ $booking->phone }}
- **Email:** {{ $booking->email ?? 'Không có' }}
- **Ngày đặt:** {{ \Carbon\Carbon::parse($booking->date)->format('d/m/Y') }}
- **Giờ đến:** {{ $booking->time }}
- **Số lượng khách:** {{ $booking->guests }} người
- **Ghi chú:** {{ $booking->notes ?? 'Không có' }}

<x-mail::button :url="config('app.url') . '/admin/bookings'">
Xem chi tiết trong Quản trị
</x-mail::button>

Cảm ơn,<br>
{{ config('app.name') }}
</x-mail::message>
