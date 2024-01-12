<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{ 
    protected $table = 'favorites';
    protected   $fillable=[
    
    
    'pharmacy_id',
    'pharmacy_name',

    'warehouse_id',
    'warehouse_name',

    'product_id',
    'product_name'
];
    use HasFactory;


    public function pharmacie()
    {
        return $this->belongsTo('App/Models/User','pharmacy_id');
    }
    public function warehouse()
    {
        return $this->belongsTo('App/Models/User','warehouse_id');
    }
    public function product()
    {
        return $this->belongsTo('App/Models/Proudct','product_id');
    }
}
