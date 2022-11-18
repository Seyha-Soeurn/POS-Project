<?php

namespace App\Observers;

use App\Models\Supplier;
use File;

class SupplierObserver
{

    public function updating(Supplier $supplier)
    {
        if ($supplier->image->url==request()->image['url']){
            dd('yes');
        }else {
            dd('no');
        }
        dd('hello');
        // $image_path = public_path($file_name);
        // File::delete($image_path);
    }
}
