<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'food';

    protected $fillable = ['category_id', 'name', 'slug', 'description', 'price', 'image', 'is_available', 'preparation_time'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function isWishlistedBy($user)
    {
        if (!$user) return false;
        
        static $userWishlistFoodIds = [];
        if (!array_key_exists($user->id, $userWishlistFoodIds)) {
            $userWishlistFoodIds[$user->id] = \App\Models\Wishlist::where('user_id', $user->id)
                ->pluck('food_id')
                ->toArray();
        }
        
        return in_array($this->id, $userWishlistFoodIds[$user->id]);
    }
}
