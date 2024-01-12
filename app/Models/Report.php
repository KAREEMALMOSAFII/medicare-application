<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'pharmacy_id',
        'pharmacy_name',
        'warehouse_id',
        'warehouse_name',
        'content'
    ];




    public function warehouses()
    {
        return $this->belongsTo('App/Models/User','warehouse_id');
    }
    public function pharmacies()
    {
        return $this->belongsTo('App/Models/User','pharmacy_id');
    }
}
