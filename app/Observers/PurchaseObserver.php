<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Purchase;
use PhpParser\Node\Expr\FuncCall;

use function PHPSTORM_META\elementType;

class PurchaseObserver
{

    public function created(Purchase $purchase)
    {

        $purchase->update([
            "purchase_code" => $purchase->id,
        ]);
    }


    public function deleting(Purchase $purchase){
        $purchase = Purchase::with('products')->find($purchase->id); 
        $purchase->products()->detach();
    }
}
