<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/user', function (Request $request) {
    $user = \App\Models\User::get()->where('api_token', '=', $request['token']);
    $user2 = null;
    foreach ($user as $user1) {
        $user2 = $user1;
    }
    return $user2;
});

Route::post('/signin', [AuthController::class, 'signin']);
Route::post('/signup', [AuthController::class, 'signup']);

// Categories

Route::get('/getcategories', [CategoriesController::class, 'getCategories']);

// Products

Route::post('/getproducts', [ProductsController::class, 'getProducts']);
Route::post('/getproduct', [ProductsController::class, 'getProduct']);
Route::post('/addcartproduct', [CartController::class, 'addCartProduct']);
Route::post('/getcartproducts', [CartController::class, 'getCartProducts']);
Route::post('/removecartproducts', [CartController::class, 'removeCartProducts']);

// Order

Route::post('/addorder', [OrdersController::class, 'addOrder']);

Route::get('lala', function () {
   return Hash::make('sergo');
});
