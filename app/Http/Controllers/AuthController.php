<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signin(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            if($user['api_token'] != null) {
                return response()->json([
                    'token' => $user->api_token
                ], 202);
            } else {
                $user->api_token = Hash::make($this->getRandomString(600));
                $user->expires_at = today()->addMonth();
                $user->save();
                return response()->json([
                    'token' => $user->api_token
                ], 202);
            }
            // Удалось зайти (сгенерить токен и вернуть)

        } else {
            return response()->json([
                'message' => 'Неверные данные!'
            ], 401);
            // Отказано
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->api_token = null;
        $user->save();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }


    public function signup(Request $request)
    {
        $userdata = request()->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'unique:users,email',
            'password' => 'required',
        ], ['required' => 'Не может быть пустым'], ['unique:users,email' => 'Пользователь с такой почтой уже существует']);

        $id = 1;

        try {
            $id = User::get()->last()->id + 1;
        } catch (\Exception $exception) {
            $id = 1;
        }
        try {
            $user = new User();
            $user['id'] = $id;
            $user['name'] = $userdata['name'];
            $user['surname'] = $userdata['surname'];
            $user['email'] = $userdata['email'];
            $user['password'] = Hash::make($userdata['password']);
            $user['permission_id'] = 1;
            $user->save();
        } catch (\Exception $e) {
            return $e;
        }

        return $user;
    }
    function getRandomString($n) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
}
