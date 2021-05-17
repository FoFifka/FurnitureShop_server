<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function addOrder(Request $request) {
        $products = $request->products;
        $order = new Order();
        $order['user_id'] = $request->user->id;
        $order['status_id'] = 1;
        $order->save();
        foreach ($products as $product) {
            $order_product = new OrderProduct();
            $order_product['order_id'] = $order['id'];
            $order_product['product_id'] = $product['id'];
            $order_product->save();
        }
        return response()->json([
           'message' => 'Заказ успешно совершён'
        ]);
    }
}
