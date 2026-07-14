<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;

class OrderController extends Controller
{
    public function prepareCheckout(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'items' => 'required|json'
        ]);

        $items = json_decode($validated['items'], true);
        
        if (empty($items)) {
            return back()->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Store checkout data in session
        session(['checkout_data' => [
            'table_id' => $validated['table_id'],
            'items' => $items
        ]]);

        return redirect()->route('checkout.index');
    }

    public function checkout()
    {
        $checkoutData = session('checkout_data');
        
        if (!$checkoutData) {
            return redirect()->route('order_at_table')->with('warning', 'Không tìm thấy thông tin đơn hàng. Vui lòng thử lại.');
        }

        return view('checkout', [
            'tableId' => $checkoutData['table_id'],
            'items' => $checkoutData['items']
        ]);
    }

    public function store(Request $request)
    {
        $checkoutData = session('checkout_data');
        
        if (!$checkoutData) {
            return redirect()->route('order_at_table')->with('error', 'Phiên đặt món đã hết hạn. Vui lòng thử lại.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,transfer'
        ]);

        $items = $checkoutData['items'];
        $tableId = $checkoutData['table_id'];

        $totalPrice = 0;
        foreach ($items as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'table_id' => $tableId,
            'total_price' => $totalPrice,
            'status' => 'new',
            'payment_status' => 'pending',
            'payment_method' => $validated['payment_method']
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'food_id' => $item['id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price']
            ]);
        }

        // Clear checkout session
        session()->forget('checkout_data');

        // Redirect to tracking page
        return redirect()->route('order.track', ['order' => $order->id]);
    }

    public function track(Order $order)
    {
        // Ensure the order belongs to the user or table logic
        if ($order->user_id) {
            if ($order->user_id !== auth()->id()) {
                abort(403, 'Không có quyền truy cập');
            }
        } else {
            // Guest order
            if ((string)$order->table_id !== (string)session('table_id')) {
                abort(403, 'Không có quyền truy cập (Sai bàn)');
            }
        }

        return view('tracking.order', compact('order'));
    }

    public function showTransferQR(Order $order)
    {
        // Ensure the order belongs to the user or table logic
        if ($order->user_id) {
            if ($order->user_id !== auth()->id()) {
                abort(403, 'Không có quyền truy cập');
            }
        } else {
            // Guest order
            if ((string)$order->table_id !== (string)session('table_id')) {
                abort(403, 'Không có quyền truy cập (Sai bàn)');
            }
        }

        $bankId = env('VIETQR_BANK_ID', 'MB');
        $accountNo = env('VIETQR_ACCOUNT_NO', '0123456789');
        $accountName = env('VIETQR_ACCOUNT_NAME', 'NGUYEN VAN A');
        $amount = (int) ($order->total_price * 1000); // Because price is stored as e.g. 50.00 representing 50k
        $addInfo = 'Thanh toan don hang ' . $order->id;

        // Generate VietQR URL
        $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-compact2.png?amount={$amount}&addInfo=" . urlencode($addInfo) . "&accountName=" . urlencode($accountName);

        return view('checkout_transfer', compact('order', 'qrUrl', 'amount', 'addInfo', 'accountName', 'accountNo', 'bankId'));
    }

    public function getStatus(Order $order)
    {
        // Optional: Add simple authorization here if needed
        return response()->json([
            'status' => $order->status,
            'payment_status' => $order->payment_status,
        ]);
    }
}
