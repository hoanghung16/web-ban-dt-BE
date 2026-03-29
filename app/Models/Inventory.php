<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $primaryKey = 'ProductId';
    public $incrementing = false;
    protected $fillable = ['ProductId', 'QuantityInStock'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductId', 'id');
    }
}
