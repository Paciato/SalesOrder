<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Http\Request;

class SalesOrderApiController extends Controller
{
    public function index()
    {
        return response()->json(
            SalesOrder::with('customer', 'items.product')->get(),
            200
        );
    }

    public function show($id)
    {
        return response()->json(
            SalesOrder::with('customer', 'items.product')->findOrFail($id),
            200
        );
    }

    public function store(Request $request)
    {
        $order = SalesOrder::create([
            'customer_id' => $request->customer_id,
            'order_date' => $request->order_date,
            'total_amount' => $request->total_amount,
            'status' => $request->status,
        ]);

        // items
        foreach ($request->items as $item) {
            SalesOrderItem::create([
                'sales_order_id' => $order->id,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
            ]);
        }

        return response()->json($order->load('items'), 201);
    }

    public function update(Request $request, $id)
    {
        $order = SalesOrder::findOrFail($id);
        $order->update($request->except('items'));

        // update items
        if ($request->has('items')) {
            SalesOrderItem::where('sales_order_id', $id)->delete();

            foreach ($request->items as $item) {
                SalesOrderItem::create([
                    'sales_order_id' => $id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                ]);
            }
        }

        return response()->json($order->load('items'), 200);
    }

    public function destroy($id)
    {
        SalesOrder::destroy($id);
        return response()->json(['message' => 'Deleted'], 200);
    }
}
