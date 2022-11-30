<?php

namespace App\Models;

use App\Models\User;
use App\Models\Customer;
use App\Models\OrderProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function details()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }

    // Getters
    public function getCustomer()
    {
        return Customer::find($this->customer_id);
    }

    public function getSeller()
    {
        return User::find($this->seller_id);
    }

    public function getDetails()
    {
        return OrderProduct::where('order_id', $this->id)->get();
    }
}
