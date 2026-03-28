<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'userid',
        'orderdate',
        'status',
        'paymentstatus',
        'totalprice',
        'shipname',
        'shipaddress',
        'shipphone'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'orderid');
    }

    // Method để tính tổng từ items (cho business logic)
    public function calculateTotal()
    {
        return $this->items()->sum(DB::raw('quantity * unitprice'));
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('userid', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('paymentstatus', 'Unpaid');
    }
}
