<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', function (Request $request) {
    try {
        $params = [
            'name' => $request->input('name'),
            'password' => $request->input('password')
        ];
        $user = \App\Models\User::where('name', $params['name'])->first();
        if (blank($user) || !\Illuminate\Support\Facades\Hash::check($params['password'], $user->password)) {
            return response([
                'message' => 'username or password 錯誤'
            ], \Illuminate\Http\Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        return response([
            'user'  => $user,
            'token' => $token
        ], \Illuminate\Http\Response::HTTP_CREATED);
    } catch (Throwable $e) {
        dd($e);
    }
});
