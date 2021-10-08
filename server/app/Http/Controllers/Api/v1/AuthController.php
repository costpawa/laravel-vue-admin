<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login (LoginRequest $request):ProfileResource
    {
        if (Auth::attempt($request->validated())) {
            $request->session()->regenerate();
            $user = auth()->user();
            return new ProfileResource($user);
        }

        return response([
            'message' => 'Email or password is wrong',
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response([
            'message' => 'Logged out'
        ], 200);
    }
}
