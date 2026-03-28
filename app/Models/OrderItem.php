<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['orderid', 'productid', 'quantity', 'unitprice'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'orderid');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'productid');
    }
}
