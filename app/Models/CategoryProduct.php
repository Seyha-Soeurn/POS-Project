<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryProduct extends Model
{
    use HasFactory, CrudTrait;

    protected $fillable =
    [
        'category_id',
        'product_id',
    ];

    // Relations
    public function product()
    {
        return $this->belongsTo(Order::class);
    }
    public function category()
    {
        return $this->belongsTo(Product::class);
    }
}
