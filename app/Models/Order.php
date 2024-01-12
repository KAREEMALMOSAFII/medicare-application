<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    use HasFactory;
    protected   $fillable=[
        'user_id', 'status', 'pay_status', 'warehouse_id', 'content', 'year', 'month','warehouse_name','pharmacy_name'
    ];
    public function warehouse()
    {
        return $this->belongsTo('App/Models/User','warehouse_id');
    }

    public function pharmacy()
    {
        return $this->belongsTo('App/Models/Product','user_id');
    }
    public function products()
    {
        return $this->belongsToMany('App/Models/Product','product_id');
    }
}
