<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Http\Request;

class SalesOrderItemApiController extends Controller
{
    public function index($orderId)
    {
        $order = SalesOrder::with('items')->findOrFail($orderId);
        return response()->json($order->items);
    }

    public function store(Request $request, $orderId)
    {
        $order = SalesOrder::findOrFail($orderId);

        $item = SalesOrderItem::create([
            'sales_order_id' => $order->id,
            'product_id'     => $request->product_id,
            'quantity'       => $request->quantity,
            'price'          => $request->price,
        ]);

        return response()->json($item, 201);
    }
}
