<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function getCategories() {
        return Category::all();
    }

    public function addCategories(Request $request) {
        $data = $request->validate([
            'api_token' => 'required',
            'name' => 'required'
        ]);
        $users = User::get()->where('api_token', $data['api_token']);
        $user = '';
        foreach ($users as $user) {
            $this->$user= $user;
        }
        if($user == null) {
            return response()->json([
                'message' => 'User not found'
            ]);
        } else {
            if($user->permission_id >= 2) {
                $category = Category::create([
                    'name' => $data['name']
                ]);
                return $category;
            } else {
                return response()->json([
                    'message' => 'you don\'t have permission'
                ], 403);
            }
        }
    }
}
