<?php

namespace App\Http\Controllers;

use App\Models\CartProduct;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function addOrder(Request $request) {
        $cardproducts = CartProduct::get()->where('user_id', '=', $request['user_id']);
        $products = [];
        foreach ($cardproducts as $cardproduct) {
            array_push($products, Product::find($cardproduct['product_id']));
            CartProduct::destroy($cardproduct['id']);
        }
        $order = new Order();
        $order['user_id'] = $request['user_id'];
        $order['status_id'] = 1;
        $order->save();
        foreach ($products as $product) {
            $order_product = new OrderProduct();
            $order_product['order_id'] = $order['id'];
            $order_product['product_id'] = $product['id'];
            $order_product->save();
        }
//        return response()->json([
//           'message' => 'Заказ успешно совершён'
//        ]);
        return $products;

    }
}
