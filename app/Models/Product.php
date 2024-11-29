<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Define the table and primary key
    protected $table = 'products';
    protected $primaryKey = 'id';

    // Fillable fields
    protected $fillable = [
        'name',
        'price',
        'quantity',
        'category',
        'image_path',
    ];

    // Relationship with Purchase
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
