<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Purchase;

class PurchaseObserver
{
    /**
     * Handle the Purchase "created" event.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return void
     */
    public function created(Purchase $purchase)
    {
        $product = Product::find(request()->product_id);
        $purchase->product()->update([
            'stock' => $product->stock + $purchase->quantity
        ]);
    }

    /**
     * Handle the Purchase "updated" event.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return void
     */
    public function updated(Purchase $purchase)
    {
        $quantityToProcess = $purchase->quantity - $purchase->getOriginal('quantity');
        $product = Product::find(request()->product_id);
        if ($product->stock + $quantityToProcess >= 0) {
            $purchase->product()->update([
                'stock' => $product->stock + $quantityToProcess
            ]);
        } else {
            $purchase->product()->update([
                'stock' => 0
            ]);
        }
    }

    /**
     * Handle the Purchase "deleted" event.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return void
     */
    public function deleted(Purchase $purchase)
    {
        //
    }

    /**
     * Handle the Purchase "restored" event.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return void
     */
    public function restored(Purchase $purchase)
    {
        //
    }

    /**
     * Handle the Purchase "force deleted" event.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return void
     */
    public function forceDeleted(Purchase $purchase)
    {
        //
    }
}
