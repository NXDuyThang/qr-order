<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'items' => 'required|json'
        ]);

        $items = json_decode($validated['items'], true);
        
        if (empty($items)) {
            return back()->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $tableId = $validated['table_id'];

        $totalPrice = 0;
        foreach ($items as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $existingOrder = Order::where('table_id', $tableId)
            ->where('payment_status', 'pending');
        
        if (auth()->check()) {
            $existingOrder->where('user_id', auth()->id());
        }

        $order = $existingOrder->first();

        if ($order) {
            $order->total_price += $totalPrice;
            $order->status = 'new'; // Reset status to new so the kitchen is notified again
            $order->save();
        } else {
            $order = Order::create([
                'user_id' => auth()->id(),
                'table_id' => $tableId,
                'total_price' => $totalPrice,
                'status' => 'new',
                'payment_status' => 'pending',
                'payment_method' => null
            ]);
        }

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'food_id' => $item['id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'status' => 'new'
            ]);
        }

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

    public function updatePaymentMethod(Request $request, Order $order)
    {
        // Check authorization
        if ($order->user_id) {
            if ($order->user_id !== auth()->id()) {
                abort(403, 'Không có quyền truy cập');
            }
        } else {
            if ((string)$order->table_id !== (string)session('table_id')) {
                abort(403, 'Không có quyền truy cập (Sai bàn)');
            }
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,transfer'
        ]);

        $order->payment_method = $validated['payment_method'];
        $order->save();

        if ($request->payment_method === 'transfer') {
            return redirect()->route('checkout.transfer', ['order' => $order->id]);
        }

        if ($request->payment_method === 'cash') {
            return redirect('/')->with('success', 'Cảm ơn bạn đã dùng bữa! Vui lòng thanh toán tại quầy thu ngân.');
        }

        return back()->with('success', 'Đã cập nhật phương thức thanh toán.');
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
        $allServed = $order->items()->whereNotIn('status', ['served', 'completed', 'cancelled'])->count() === 0;

        // Optional: Add simple authorization here if needed
        return response()->json([
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'all_items_served' => $allServed,
        ]);
    }

    public function cancelItem(Request $request, Order $order, OrderItem $item)
    {
        // Check authorization
        if ($order->user_id && $order->user_id !== auth()->id()) {
            abort(403, 'Không có quyền truy cập');
        }

        // Ensure item belongs to order
        if ($item->order_id !== $order->id) {
            abort(404, 'Không tìm thấy món trong đơn hàng');
        }

        if (in_array($item->status, ['ready', 'served', 'completed', 'cancelled'])) {
            return back()->with('error', 'Không thể huỷ món này vì bếp đã nấu xong hoặc đã huỷ.');
        }

        // Update item status
        $item->update(['status' => 'cancelled']);

        // Update order total price
        $deductAmount = $item->unit_price * $item->quantity;
        $order->total_price -= $deductAmount;
        if ($order->total_price < 0) $order->total_price = 0;
        
        // Check if all items are cancelled
        if ($order->items()->where('status', '!=', 'cancelled')->count() === 0) {
            $order->status = 'cancelled';
        }
        
        $order->save();

        return back()->with('success', 'Đã huỷ món thành công.');
    }
}
