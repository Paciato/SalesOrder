<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesOrder extends Model
{
    protected $fillable = [
        'customer_id',
        'order_date',
        'total_amount',
        'status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function calculateTotalAmount()
    {
        return $this->items()->sum(DB::raw('qty * price'));
    }
}
