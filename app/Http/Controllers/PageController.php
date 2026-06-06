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

    public function menu() { return view('menu'); }
    public function booking() { return view('booking'); }
    public function orderAtTable(Request $request) 
    { 
        $tableId = $request->query('table_id');
        $categories = Category::with(['food' => function($query) {
            $query->where('is_available', true);
        }])->where('is_active', true)->get();
        
        return view('order_at_table', compact('categories', 'tableId')); 
    }
    public function vietnameseCuisine() { return view('portfolio'); }
    public function vietnameseCuisineDetail($slug) { return view('portfolio_detail', compact('slug')); }
    public function contact() { return view('contact'); }
}
