<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'seller_id',
        'discount',
        'amount',
        'amount_after_discount'
    ];

    // Relations
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
