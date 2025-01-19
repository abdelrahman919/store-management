<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function login (LoginRequest $request){

         $request->validated($request->all());

        if (!Auth::attempt($request->only('name', 'password'))) {
            return $this->error(null, 'Invalid Credentials',401);
        }
        
        $user = User::firstWhere('name', $request['name']);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success($token, 'Authenticated');

    }
}
