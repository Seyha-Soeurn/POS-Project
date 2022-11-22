<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\OrderProduct;

class OrderProductObserver
{
    /**
     * Handle the OrderProduct "created" event.
     *
     * @param  \App\Models\OrderProduct  $orderProduct
     * @return void
     */
    public function created(OrderProduct $orderProduct)
    {
        $product = Product::find(request()->product);
        $orderProduct->product()->update([
            'stock' => $product->stock - $orderProduct->quantity
        ]);
    }

    /**
     * Handle the OrderProduct "updated" event.
     *
     * @param  \App\Models\OrderProduct  $orderProduct
     * @return void
     */
    public function updated(OrderProduct $orderProduct)
    {
        $quantityToProcess = $orderProduct->quantity - $orderProduct->getOriginal('quantity');
        $product = Product::find(request()->product);
        if ($product->stock - $quantityToProcess >= 0) {
            $orderProduct->product()->update([
                'stock' => $product->stock - $quantityToProcess
            ]);
        } else {
            $orderProduct->product()->update([
                'stock' => 0
            ]);
        }
    }

    /**
     * Handle the OrderProduct "deleted" event.
     *
     * @param  \App\Models\OrderProduct  $orderProduct
     * @return void
     */
    public function deleted(OrderProduct $orderProduct)
    {
        //
    }

    /**
     * Handle the OrderProduct "restored" event.
     *
     * @param  \App\Models\OrderProduct  $orderProduct
     * @return void
     */
    public function restored(OrderProduct $orderProduct)
    {
        //
    }

    /**
     * Handle the OrderProduct "force deleted" event.
     *
     * @param  \App\Models\OrderProduct  $orderProduct
     * @return void
     */
    public function forceDeleted(OrderProduct $orderProduct)
    {
        //
    }
}
