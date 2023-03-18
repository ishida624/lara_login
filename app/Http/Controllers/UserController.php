<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            $params = [
                'name'     => $request->input('name'),
                'password' => $request->input('password')
            ];
            $user   = User::where('name', $params['name'])->first();
            if (blank($user) || !Hash::check($params['password'], $user->password)) {
                return response([
                    'message' => 'username or password 錯誤'
                ], Response::HTTP_UNAUTHORIZED);
            }

            $token = $user->createToken('apiToken')->plainTextToken;

            return response([
                'user'  => $user,
                'token' => $token
            ], Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            return response([
                'user'  => $user ?? '',
                'token' => $token ?? ''
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
