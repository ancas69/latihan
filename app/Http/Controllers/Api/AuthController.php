<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $req){
        $req->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'passowrd' => 'required|string|min:6'

        ]);

        $user = User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password)
        ]);
    }

    public function login(Request $req){
        $data_login = $req->only('email', 'password');
        $token = Auth::guard('api')->attempt($data_login);

        if(!$token){
            return response()->json([
                'msg' => "gagal login, periksa email atau password"
            ]);
        }

        return response()->json([
            'msg' => "login berhasil",
            'data_user' => Auth::guard('api')->user(),
            'token' => $token
        ]);

    }

    public function me(){
        return response()->json([
            'data_user' => Auth::guard('api')->user()
        ]);

    }

    public function logout(){
        return response()->json([
            'data_logout' => Auth::guard('api')->logout()
        ]);
    }

    public function refresh(){
        return response()->json([
            'data_refresh' => Auth::guard('api')->refresh()
        ]);
    }
}
