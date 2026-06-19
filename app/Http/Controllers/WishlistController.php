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
            'action' => 'nullable|in:add,remove'
        ]);

        $user = Auth::user();
        $foodId = $request->food_id;
        $action = $request->action;

        $wishlist = Wishlist::where('user_id', $user->id)
                            ->where('food_id', $foodId)
                            ->first();

        if ($action === 'remove' || ($wishlist && !$action)) {
            // Đã có thì xoá
            if ($wishlist) {
                $wishlist->delete();
            }
            return response()->json([
                'status' => 'removed',
                'message' => 'Đã xoá khỏi danh sách yêu thích'
            ]);
        } else {
            // Chưa có thì thêm
            if (!$wishlist) {
                Wishlist::create([
                    'user_id' => $user->id,
                    'food_id' => $foodId
                ]);
            }
            return response()->json([
                'status' => 'added',
                'message' => 'Đã thêm vào danh sách yêu thích'
            ]);
        }
    }
}
