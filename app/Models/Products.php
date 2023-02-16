<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'product_title',
        'image_url',
        'price',
        'rating',
        'category',
        'is_deleted',
        'web_id'
    ];
    use HasFactory;
}
