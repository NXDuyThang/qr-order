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
            ->whereNotIn('status', ['cancelled']) // Exclude cancelled etc
            ->where('payment_status', 'pending');

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

        // Redirect staff back to POS order interface
        if (auth()->check() && in_array(auth()->user()->role, ['admin', 'manager', 'waiter'])) {
            return redirect()->route('pos.table_order', ['table' => $tableId])
                ->with('success', 'Đã gửi yêu cầu đặt món tới bếp!');
        }

        // Redirect to tracking page for customers
        return redirect()->route('order.track', ['order' => $order->id]);
    }

    public function track(Order $order)
    {
        $isStaff = auth()->check() && in_array(auth()->user()->role, ['admin', 'manager', 'waiter']);
        if (!$isStaff) {
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
        }

        return view('tracking.order', compact('order'));
    }

    public function updatePaymentMethod(Request $request, Order $order)
    {
        $isStaff = auth()->check() && in_array(auth()->user()->role, ['admin', 'manager', 'waiter']);
        if (!$isStaff) {
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
            $order->update(['payment_status' => 'paid']);
            
            if ($isStaff) {
                $order->update(['status' => 'completed']);
                $order->items()->whereNotIn('status', ['cancelled'])->update(['status' => 'completed']);
                
                if ($order->table_id) {
                    $hasPendingOrders = \App\Models\Order::where('table_id', $order->table_id)
                        ->where('payment_status', 'pending')
                        ->exists();
                    if (!$hasPendingOrders) {
                        \App\Models\Table::where('id', $order->table_id)->update(['status' => 'available']);
                    }
                }
            }
            
            return back()->with('success', 'Đã ghi nhận thanh toán tiền mặt thành công. Cảm ơn quý khách!');
        }

        return back()->with('success', 'Đã cập nhật phương thức thanh toán.');
    }

    public function showTransferQR(Order $order)
    {
        $isStaff = auth()->check() && in_array(auth()->user()->role, ['admin', 'manager', 'waiter']);
        if (!$isStaff) {
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

    public function confirmTransfer(Order $order)
    {
        $isStaff = auth()->check() && in_array(auth()->user()->role, ['admin', 'manager', 'waiter']);
        if (!$isStaff) {
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
        }

        $order->update(['payment_status' => 'paid']);

        return redirect()->route('order.track', ['order' => $order->id])
            ->with('success', 'Cảm ơn quý khách! Đơn hàng của bạn đã được ghi nhận thanh toán thành công.');
    }

    public function getStatus(Order $order)
    {
        $order->refresh();
        $allServed = $order->items()->whereNotIn('status', ['served', 'completed', 'cancelled'])->count() === 0;

        $items = $order->items->map(function ($item) {
            return [
                'id' => $item->id,
                'status' => $item->status,
                'quantity' => $item->quantity,
                'updatedAtMs' => $item->updated_at->timestamp * 1000,
            ];
        });

        // Optional: Add simple authorization here if needed
        return response()->json([
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'all_items_served' => $allServed,
            'items' => $items,
        ]);
    }

    public function cancelItem(Request $request, Order $order, OrderItem $item)
    {
        $isStaff = auth()->check() && in_array(auth()->user()->role, ['admin', 'manager', 'waiter']);
        // Check authorization
        if (!$isStaff && $order->user_id && $order->user_id !== auth()->id()) {
            abort(403, 'Không có quyền truy cập');
        }

        // Ensure item belongs to order
        if ($item->order_id !== $order->id) {
            abort(404, 'Không tìm thấy món trong đơn hàng');
        }

        if ($isStaff) {
            if (in_array($item->status, ['ready', 'served', 'completed', 'cancelled'])) {
                return back()->with('error', 'Không thể huỷ món này vì bếp đã nấu xong hoặc món đã bị huỷ.');
            }
        } else {
            if ($item->status !== 'new') {
                return back()->with('error', 'Không thể huỷ món này vì bếp đã bắt đầu làm hoặc đã huỷ.');
            }
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
    public function reduceItem(Request $request, Order $order, OrderItem $item)
    {
        $isStaff = auth()->check() && in_array(auth()->user()->role, ['admin', 'manager', 'waiter']);
        if (!$isStaff && $order->user_id && $order->user_id !== auth()->id()) {
            abort(403, 'Không có quyền truy cập');
        }

        if ($item->order_id !== $order->id) {
            abort(404, 'Không tìm thấy món trong đơn hàng');
        }

        if ($isStaff) {
            if (in_array($item->status, ['ready', 'served', 'completed', 'cancelled'])) {
                return back()->with('error', 'Không thể giảm món này vì bếp đã nấu xong hoặc món đã bị huỷ.');
            }
        } else {
            if ($item->status !== 'new') {
                return back()->with('error', 'Không thể giảm món này vì bếp đã bắt đầu làm hoặc đã huỷ.');
            }
        }

        if ($item->quantity > 1) {
            $item->quantity -= 1;
            $item->save();
            
            $order->total_price -= $item->unit_price;
            $order->save();
            
            return back()->with('success', 'Đã giảm 1 số lượng món.');
        } else {
            // Huỷ món luôn nếu số lượng là 1
            $item->update(['status' => 'cancelled']);
            
            $order->total_price -= $item->unit_price;
            $order->save();
            
            return back()->with('success', 'Đã huỷ món thành công.');
        }
    }

    public function updateQuantity(Request $request, Order $order, OrderItem $item)
    {
        $isStaff = auth()->check() && in_array(auth()->user()->role, ['admin', 'manager', 'waiter']);
        if (!$isStaff && $order->user_id && $order->user_id !== auth()->id()) {
            abort(403, 'Không có quyền truy cập');
        }

        if ($item->order_id !== $order->id) {
            abort(404, 'Không tìm thấy món trong đơn hàng');
        }

        if ($isStaff) {
            if (in_array($item->status, ['ready', 'served', 'completed', 'cancelled'])) {
                return back()->with('error', 'Không thể thay đổi món này vì bếp đã nấu xong hoặc món đã bị huỷ.');
            }
        } else {
            if ($item->status !== 'new') {
                return back()->with('error', 'Không thể thay đổi món này vì bếp đã bắt đầu làm hoặc đã huỷ.');
            }
        }

        $newQuantity = (int)$request->input('quantity');
        
        if ($newQuantity < 1) {
            // Huỷ món
            return $this->cancelItem($request, $order, $item);
        }

        if ($newQuantity < $item->quantity) {
            $difference = $item->quantity - $newQuantity;
            $item->quantity = $newQuantity;
            $item->save();
            
            $order->total_price -= ($item->unit_price * $difference);
            if ($order->total_price < 0) $order->total_price = 0;
            $order->save();
            
            return back()->with('success', 'Đã giảm số lượng món thành công.');
        }

        if ($newQuantity > $item->quantity) {
            $difference = $newQuantity - $item->quantity;
            $item->quantity = $newQuantity;
            $item->save();
            
            $order->total_price += ($item->unit_price * $difference);
            $order->save();
            
            return back()->with('success', 'Đã tăng số lượng món thành công.');
        }

        return back();
    }
}
