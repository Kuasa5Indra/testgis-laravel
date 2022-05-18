<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ClientResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->only(['email', 'password']);
        if(!Auth::attempt($data)) {
            return ClientResponse::errorResponse(Response::HTTP_UNAUTHORIZED, 'Unauthorized access');
        }
        $user = User::where('email', $data['email'])->firstOrFail();
        $token = $user->createToken('web-token')->plainTextToken;
        return ClientResponse::successResponse(Response::HTTP_OK, 'Login Success', ['access_token' => $token, 'type' => 'Bearer']);
    }

    public function register(Request $request)
    {
        $data = $request->only(['name', 'email', 'password']);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
        $token = $user->createToken('web-token')->plainTextToken;
        return ClientResponse::successResponse(Response::HTTP_OK, 'Registration Success', ['access_token' => $token, 'type' => 'Bearer']);
    }

    public function show(){
        return ClientResponse::successResponse(Response::HTTP_OK, 'Success show user', Auth::user());
    }

    public function logout()
    {
        auth('sanctum')->user()->tokens()->delete();
        return ClientResponse::successResponse(Response::HTTP_OK, 'Logout Success');
    }
}
