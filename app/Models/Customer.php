<?php

namespace App\Models;

use App\Models\Image;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $hidden = 
    [
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address'
    ];

    public $importable =
    [
        'name',
        'email',
        'phone',
        'address'
    ];

    // Relations
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Accessors
    public function getProfileAttribute()
    {
        return optional($this->image)->url;
    }

    // Getters
    public function getProfile()
    {
        return Image::where('imageable_type', Customer::class)->where('imageable_id', $this->id)->first();
    }

    public function getOrders()
    {
        return Order::where('customer_id', $this->id)->get();
    }
}
