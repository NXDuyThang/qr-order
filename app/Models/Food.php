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
        return $this->wishlists()->where('user_id', $user->id)->exists();
    }
}
