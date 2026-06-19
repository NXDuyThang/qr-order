<x-mail::message>
# Xin chào {{ $booking->name }},

Hệ thống đã nhận được yêu cầu đặt bàn của bạn. Dưới đây là thông tin chi tiết:

- **Ngày đặt:** {{ \Carbon\Carbon::parse($booking->date)->format('d/m/Y') }}
- **Giờ đến:** {{ $booking->time }}
- **Số lượng khách:** {{ $booking->guests }} người
- **Ghi chú:** {{ $booking->notes ?? 'Không có' }}
- **Trạng thái hiện tại:** Chờ xác nhận

Nhà hàng sẽ sớm liên hệ với bạn qua số điện thoại **{{ $booking->phone }}** để xác nhận lại thông tin. Xin cảm ơn bạn đã lựa chọn dịch vụ của chúng tôi!

Cảm ơn,<br>
Nhà Hàng {{ config('app.name') }}
</x-mail::message>
