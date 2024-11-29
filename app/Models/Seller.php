<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'sellers';

    // The attributes that are mass assignable
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'phone', 'shop_name', 'address'
    ];

    // Hash the password before saving to the database
    public static function boot()
    {
        parent::boot();

        static::creating(function ($seller) {
            $seller->password = bcrypt($seller->password); // Hash the password
        });
    }
}