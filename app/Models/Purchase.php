<?php

namespace App\Models;

use App\Models\Product;
use App\Models\PurchaseProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $fillable =
    [
        'supplier_id',
        'amount',
        'purchase_code',
    ];
    public function products()
    {
        return $this->belongsToMany(Product::class,"purchase_products");
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
