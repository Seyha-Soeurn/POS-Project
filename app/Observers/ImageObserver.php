<?php

namespace App\Observers;

use App\Models\Image;
use App\Models\Supplier;
use File;

class ImageObserver
{
   public function updating(Image $image){
      if ($image['url']!=null){
         $old_image = $image->getOriginal('url');
         if ($old_image !== "upload/folder_1/folder_2/default_profile"){
            if (request()->image['url'] !== $image['url']){
               \Storage::disk('upload')->delete($old_image);
            }
         }
      }
   }
}