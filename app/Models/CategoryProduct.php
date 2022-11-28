<?php

namespace App\Models;

use App\Models\Category;
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

    // Getters
    public function getCategory()
    {
        return Category::find($this->category_id);
    }
}
