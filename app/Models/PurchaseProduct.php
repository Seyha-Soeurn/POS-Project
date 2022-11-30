<?php

namespace App\Models;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseProduct extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'purchase_id',
        'prouduct_id',
        'quantity',
        'amount',
    ];
    // public function product()
    // {
    //     return $this->belongsTo(Purchase::class);
    // }

}
