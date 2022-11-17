<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $fillable =
    [
        'supplier_id',
        'product_id',
        'quantity',
        'amount',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
