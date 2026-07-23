<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .receipt-card {
            background: white;
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px dashed #e5e7eb;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 24px;
            color: #111827;
        }
        .header p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }
        .info {
            margin-bottom: 20px;
            font-size: 14px;
            color: #374151;
        }
        .info div {
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .items th {
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
            color: #6b7280;
            font-weight: 500;
        }
        .items td {
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
            color: #111827;
        }
        .items .price {
            text-align: right;
        }
        .total-section {
            border-top: 2px dashed #e5e7eb;
            padding-top: 15px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: bold;
            color: #111827;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #6b7280;
        }
        .print-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #3b82f6;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
            border: none;
            cursor: pointer;
        }
        .print-btn:hover {
            background-color: #2563eb;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .receipt-card {
                box-shadow: none;
                max-width: 100%;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="receipt-card">
        <div class="header">
            <h1>NKS Restaurant</h1>
            <p>Hóa đơn thanh toán</p>
        </div>
        
        <div class="info">
            <div>
                <span>Số phiếu:</span>
                <strong>#{{ $order->id }}</strong>
            </div>
            <div>
                <span>Bàn:</span>
                <strong>{{ $order->table ? $order->table->name : 'Mang đi' }}</strong>
            </div>
            <div>
                <span>Khách hàng:</span>
                <strong>{{ $order->user ? $order->user->name : 'Khách vãng lai' }}</strong>
            </div>
            <div>
                <span>Ngày giờ:</span>
                <strong>{{ $order->updated_at->format('d/m/Y H:i') }}</strong>
            </div>
            <div>
                <span>Phương thức:</span>
                <strong>{{ $order->payment_method === 'cash' ? 'Tiền mặt' : ($order->payment_method === 'transfer' ? 'Chuyển khoản' : 'Thẻ') }}</strong>
            </div>
        </div>
        
        <table class="items">
            <thead>
                <tr>
                    <th>Món ăn</th>
                    <th style="text-align: center;">SL</th>
                    <th class="price">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items->where('status', '!=', 'cancelled') as $item)
                <tr>
                    <td>{{ $item->food->name }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td class="price">{{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}đ</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="total-section">
            <div class="total-row">
                <span>Tổng cộng:</span>
                <span>{{ number_format($order->total_price, 0, ',', '.') }}đ</span>
            </div>
        </div>
        
        <div class="footer">
            <p>Cảm ơn quý khách và hẹn gặp lại!</p>
            <p style="font-size: 12px; margin-top: 5px;">Powered by NKS</p>
        </div>
        
        <button class="print-btn" onclick="window.print()">In Hóa Đơn</button>
    </div>

</body>
</html>
