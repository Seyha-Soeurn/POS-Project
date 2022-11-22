<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Purchase;
use App\Models\OrderProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $fillable =
    [
        'name',
        'price',
        'stock',
        'product_code',
    ];

    // Relations
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_products');
    }
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
