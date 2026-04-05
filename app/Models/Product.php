<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'categoryid',
        'name',
        'price',
        'saleprice',
        'IsOnSale',
        'IsPublished',
        'imageUrl',
        'cloudinary_public_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryid');
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'ProductId', 'id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'productid');
    }

    public function scopePublished($query)
    {
        return $query->where('IsPublished', true);
    }

    public function scopeOnSale($query)
    {
        return $query->where('IsOnSale', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('categoryid', $categoryId);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where('name', 'like', "%{$keyword}%");
    }
}
