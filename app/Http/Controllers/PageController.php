<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Food;

class PageController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function restaurantHome()
    {
        $categories = Category::where('is_active', 'true')->get();
        // Get some foods for the "From Our Menu" section
        $specialFoods = Food::where('is_available', 'true')->take(6)->get(); 
        
        return view('home', compact('categories', 'specialFoods'));
    }

    public function menu() { 
        $categories = Category::with(['food' => function($query) {
            $query->where('is_available', 'true');
        }])->where('is_active', 'true')->get();
        
        return view('menu', compact('categories')); 
    }

    public function productDetail($slug)
    {
        $food = Food::with('category')->where('slug', $slug)->firstOrFail();
        return view('product_detail', compact('food'));
    }
    public function booking() { return view('booking'); }
    public function orderAtTable(Request $request) 
    { 
        // Yêu cầu bắt buộc đăng nhập để đặt món
        if (!\Illuminate\Support\Facades\Auth::check() && !\Illuminate\Support\Facades\Session::has('access_token')) {
            $tableId = $request->query('table_id');
            if ($tableId) {
                session(['table_id' => $tableId]);
            }
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thực hiện đặt món tại bàn!');
        }

        $tableId = $request->query('table_id');
        if ($tableId) {
            $tableExists = \App\Models\Table::where('id', $tableId)->exists();
            if ($tableExists) {
                session(['table_id' => $tableId]);
            } else {
                $tableId = session('table_id');
            }
        } else {
            $tableId = session('table_id');
        }

        $tables = \App\Models\Table::all();
        
        if ($tableId && !$tables->contains('id', $tableId)) {
            session()->forget('table_id');
            $tableId = null;
        }

        // Yêu cầu bắt buộc phải quét mã QR chọn bàn trước
        if (!$tableId) {
            return redirect()->route('welcome')->with('error', 'Vui lòng quét mã QR tại bàn để chọn bàn trước khi đặt món!');
        }

        $categories = Category::with(['food' => function($query) {
            $query->where('is_available', 'true');
        }])->where('is_active', 'true')->get();
        
        $activeOrder = null;
        if ($tableId) {
            $currentUserId = \Illuminate\Support\Facades\Auth::id();
            
            // Check if table is occupied by someone else
            $existingOrder = \App\Models\Order::where('table_id', $tableId)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->where('payment_status', 'pending')
                ->first();
                
            if ($existingOrder && $existingOrder->user_id !== $currentUserId) {
                return redirect()->route('welcome')->with('error', 'Bàn này hiện đang có khách sử dụng, vui lòng chọn bàn khác!');
            }
            
            $activeOrder = $existingOrder;
        }
        
        return view('order_at_table', compact('categories', 'tableId', 'tables', 'activeOrder'));
    }
    public function vietnameseCuisine(Request $request) { 
        $categories = Category::where('is_active', 'true')->get();
        $query = Food::with('category')->where('is_available', 'true');
        
        if ($request->has('category') && $request->category !== 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        $foods = $query->paginate(6);
        $foods->appends($request->query());
        
        return view('portfolio', compact('categories', 'foods')); 
    }
    public function vietnameseCuisineDetail($slug) { 
        $food = Food::with('category')->where('slug', $slug)->firstOrFail();
        $relatedFoods = Food::where('category_id', $food->category_id)
            ->where('id', '!=', $food->id)
            ->where('is_available', 'true')
            ->take(4)->get();
        return view('portfolio_detail', compact('food', 'relatedFoods')); 
    }
    public function contact() { return view('contact'); }
}
