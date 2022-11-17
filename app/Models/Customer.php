<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $hidden = 
    [
        'created_at',
        'updated_at',
    ];
    protected $fillable = 
    [
        'name',
        'phone',
        'email',
        'address'
    ];
}
