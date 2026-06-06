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

        $totalPrice = 0;
        foreach ($items as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'table_id' => $validated['table_id'],
            'total_price' => $totalPrice,
            'status' => 'new',
            'payment_status' => 'pending'
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'food_id' => $item['id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price']
            ]);
        }

        return redirect()->route('order_at_table', ['table_id' => $validated['table_id']])->with('success', 'Đặt món thành công! Vui lòng chờ trong giây lát.');
    }
}
