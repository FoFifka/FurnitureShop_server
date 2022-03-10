<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
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

    public function addProduct(Request $request) {
        $data = $request->validate([
            'api_token' => 'required',
            'name' => 'required|max:255',
            'description' => 'required',
            'price' =>'required|integer',
            'category_id' => 'required|integer'
        ]);
        $users = User::get()->where('api_token', $data['api_token']);
        $user = '';
        foreach ($users as $user) {
            $this->$user= $user;
        }

        if($user->permission_id >= 2) {
            $product = Product::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'old_price' => $data['price'],
                'category_id' => $data['category_id'],
            ]);
            return $product;
        } else {
            return response()->json([
                'message' => 'you don\'t have permission'
            ], 403);
        }
    }

    public function deleteProduct(Request $request) {
        $data = $request->validate([
            'api_token' => 'required',
            'product_id' => 'required'
        ]);
        $users = User::get()->where('api_token', $data['api_token']);
        $user = '';
        foreach ($users as $user) {
            $this->$user= $user;
        }
        if($user->permission_id >= 2 ) {
            Product::destroy($data['product_id']);
            return response()->json([
                'message' => 'Product has been deleted',
            ]);
        } else {
            return response()->json([
                'message' => 'You don\'t have permission ',
            ], 403);
        }
    }
}
