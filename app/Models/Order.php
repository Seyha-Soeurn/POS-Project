<?php

namespace App\Models;

use App\Models\OrderProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    protected $fillable = 
    [
        'customer_id',
        'seller_id',
        'discount',
        'amount',
        'amount_after_discount'
    ];

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
}
