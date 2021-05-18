<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function getProducts(Request $request) {

        $products = Product::get()->where('category_id', '=', $request['category_id']);
        $products2 = [];
        foreach ($products as $product) {
            array_push($products2, [
                "id" => $product['id'],
                "name" => $product['name'],
                "description" => $product['description'],
                "image" => $product['image'],
                "price" => $product['price'],
                "old_price" => $product['old_price'],
            ]);
        }
        return $products2;
    }
    public function getProduct(Request $request) {
        return Product::find($request['product_id']);
    }
}
