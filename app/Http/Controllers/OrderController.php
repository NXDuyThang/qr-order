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

        return redirect()->route('order_at_table', ['table_id' => $tableId])->with('success', 'Thanh toán thành công! Đơn hàng của bạn đã được ghi nhận. Vui lòng chờ trong giây lát.');
    }
}
