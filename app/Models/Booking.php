<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'date', 'time', 'guests', 'table_id', 'notes', 'status'];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
