<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$food = App\Models\Food::with('category')->where('slug', 'bun-rieu-cua')->firstOrFail();
$relatedFoods = App\Models\Food::where('category_id', $food->category_id)
    ->where('id', '!=', $food->id)
    ->where('is_available', true)
    ->take(4)->get();

echo view('portfolio_detail', compact('food', 'relatedFoods'))->render();
