<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wishlists = Wishlist::with('food.category')->where('user_id', $user->id)->get();
        
        $foods = $wishlists->map(function ($wishlist) {
            return $wishlist->food;
        })->filter();

        // Tái sử dụng phân trang hoặc hiển thị trực tiếp
        return view('wishlist.index', compact('foods'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:food,id',
        ]);

        $user = Auth::user();
        $foodId = $request->food_id;

        $wishlist = Wishlist::where('user_id', $user->id)
                            ->where('food_id', $foodId)
                            ->first();

        if ($wishlist) {
            // Đã có thì xoá
            $wishlist->delete();
            return response()->json([
                'status' => 'removed',
                'message' => 'Đã xoá khỏi danh sách yêu thích'
            ]);
        } else {
            // Chưa có thì thêm
            Wishlist::create([
                'user_id' => $user->id,
                'food_id' => $foodId
            ]);
            return response()->json([
                'status' => 'added',
                'message' => 'Đã thêm vào danh sách yêu thích'
            ]);
        }
    }
}
