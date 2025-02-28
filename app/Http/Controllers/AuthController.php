<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class AuthController extends BaseController
{
    // NOTE: Allows multiple logins for the same user  
    public function login (LoginRequest $request){

         $request->validated($request->all());

        if (!Auth::attempt($request->only('name', 'password'))) {
            return $this->error(null, 'Invalid Credentials',401);
        }
        
        $user = User::firstWhere('name', $request['name']);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success($token, 'Authenticated');

    }

    public function logout(Request $request){
        $user = $request->user();

        if (Shift::active($user->id)) {
            throw new InvalidArgumentException("User has open shift please close shift before loggin out.", 1);
        }

        $user->currentAccessToken()->delete();
        
        return $this->success();
    }

}
