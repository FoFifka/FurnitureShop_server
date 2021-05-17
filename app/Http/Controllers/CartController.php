<?php

namespace App\Http\Controllers;

use App\Models\CartProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\String_;

class CartController extends Controller
{
    public function addCartProduct(Request $request) {
        $cartproduct = new CartProduct();
        $cartproduct['user_id'] = $request['user_id'];
        $cartproduct['product_id'] = $request['product_id'];
        $cartproduct['count'] = $request['count'];
        $cartproduct->save();

        return ['message' => 'Товар добавлен в корзину'];
    }

    public function getCartProducts(Request $request) {
        $cartproducts = CartProduct::get()->where('user_id', '=', $request['user_id']);
        $cartproducts2 = [];

        foreach ($cartproducts as $cartproduct) {
            $product = Product::find($cartproduct['product_id']);
            array_push($cartproducts2, [
                'id' => $cartproduct['id'],
                'product_id' => $cartproduct['product_id'],
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'image' => $product['image'],
                'count' => $cartproduct['count'],

            ]);
        }

        return $cartproducts2;


    }
    public function removeCartProducts(Request $request) {
        $array = [];
        try {
            for($i = 0; $i < 5; $i++) {
                array_push($array, $request[$i]);
                if(is_null($request[$i])) {
                   break;
                }
            }
        } catch (\Exception $e) {;
        }
        $products = [];
        $user = [];
        for($i = 0; $i < count($array)-1;$i++) {
            $item = $array[$i];
            if($i+1 == count($array)-1) {
                 $product = CartProduct::find($item['product_id']);
                 $user = User::find($product['user_id']);
            }
            CartProduct::destroy($item['product_id']);
            if($i+1 == count($array)-1) {
                $products = CartProduct::get()->where('user_id', '=', $user['id']);
            }
        }
        return $products;
    }
}
