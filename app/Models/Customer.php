<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'name',
        'phone',
        'email',
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
}
