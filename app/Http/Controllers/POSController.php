<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Order;
use App\Models\User;

class POSController extends Controller
{
    public function index()
    {
        $tables = Table::all();
        // Cần pass thêm danh sách user để làm autocomplete cho Form
        $users = User::whereNotNull('phone')->get();
        return view('pos.index', compact('tables', 'users'));
    }

    public function createOrder(Request $request, Table $table)
    {
        $request->validate([
            'phone' => 'required|string',
            'name' => 'nullable|string',
            'email' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            $user = User::create([
                'name' => $request->name ?: 'Khách hàng',
                'phone' => $request->phone,
                'email' => $request->phone . '@gmail.com',
                'password' => bcrypt('123456'),
                'role' => 'customer',
            ]);
        }

        $order = Order::create([
            'table_id' => $table->id,
            'user_id' => $user->id,
            'status' => 'new',
            'payment_status' => 'pending',
            'total_price' => 0,
        ]);

        $table->update(['status' => 'occupied']);

        return redirect()->route('pos.table_order', ['table' => $table->id]);
    }

    public function tableOrder(Table $table)
    {
        $activeOrder = Order::with(['user', 'items.food'])
            ->where('table_id', $table->id)
            ->whereNotIn('status', ['cancelled'])
            ->where('payment_status', 'pending')
            ->latest()
            ->first();

        $categories = \App\Models\Category::with(['food' => function($query) {
            $query->where('is_available', 'true');
        }])->where('is_active', 'true')->get();

        return view('pos.order', compact('table', 'activeOrder', 'categories'));
    }

    public function serveItem(\App\Models\OrderItem $item)
    {
        if ($item->status === 'ready') {
            $item->update(['status' => 'served']);
            
            // Check if all items in order are served
            $order = $item->order;
            $allServed = $order->items()->whereNotIn('status', ['served', 'cancelled'])->count() === 0;
            if ($allServed && $order->status !== 'completed') {
                $order->update(['status' => 'served']);
            }
            
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Item is not ready yet']);
    }
}
