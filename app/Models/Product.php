<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    use HasFactory;
    protected   $fillable=[
        'name_scientific',
        'name_trade',
        'type',
        'company',
        'quantity',
        'ex_date',
        'price',
        'warehouse_id',
        'warehouse_name'
    ];
    public function warehouse()
    {
        return $this->belongsToMany('App/Models/User','warehouse_id');
    }

    public function orders()
    {
        return $this->hasMany('App/Models/Order');
    }

    public function favorite()
    {
        return $this->hasOne('App/Models/Favorite');
    }
}
