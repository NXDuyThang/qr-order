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
        $categories = Category::where('is_active', true)->get();
        // Get some foods for the "From Our Menu" section
        $specialFoods = Food::where('is_available', true)->take(6)->get(); 
        
        return view('home', compact('categories', 'specialFoods'));
    }

    public function menu() { 
        $categories = Category::with(['food' => function($query) {
            $query->where('is_available', true);
        }])->where('is_active', true)->get();
        
        return view('menu', compact('categories')); 
    }
    public function booking() { return view('booking'); }
    public function orderAtTable(Request $request) 
    { 
        $tableId = $request->query('table_id');
        $categories = Category::with(['food' => function($query) {
            $query->where('is_available', true);
        }])->where('is_active', true)->get();
        
        return view('order_at_table', compact('categories', 'tableId'));
    }
    public function vietnameseCuisine() { 
        $categories = Category::where('is_active', true)->get();
        $foods = Food::with('category')->where('is_available', true)->get();
        return view('portfolio', compact('categories', 'foods')); 
    }
    public function vietnameseCuisineDetail($slug) { 
        $food = Food::with('category')->where('slug', $slug)->firstOrFail();
        $relatedFoods = Food::where('category_id', $food->category_id)
            ->where('id', '!=', $food->id)
            ->where('is_available', true)
            ->take(4)->get();
        return view('portfolio_detail', compact('food', 'relatedFoods')); 
    }
    public function contact() { return view('contact'); }
}
