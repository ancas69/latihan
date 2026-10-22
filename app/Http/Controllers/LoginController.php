<?php

namespace App\Http\Controllers;

use App\Models\User;

class LoginController extends Controller
{
    public function halamanLogin(){
        $data_user = User::all();
        return view('login.index');
    }
}
