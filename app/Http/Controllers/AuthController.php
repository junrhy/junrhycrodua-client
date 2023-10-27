<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $brand_id = "f6f2202a-6d82-11ee-91c0-0242ac120005";

        $params = [
            "email" => $request->email,
            "password" => $request->password,
            "brand_id" => $brand_id
        ];

        $response = Http::withHeaders([
                        'Accept' => 'application/json'
                    ])->replaceHeaders([
                        'Content-Type' => 'application/json'
                    ])->withBody(
                        json_encode($params)
                    )->post(env('ENDPOINT_HOST') . '/api/login');

        if ($response->json('success')) {
            Redis::set('current_user:id',  $response->json('id'));
            Redis::set('current_user:name',  $response->json('name'));
            Redis::set('current_user:email',  $response->json('email'));
            Redis::set('current_user:brand_id',  $response->json('brand_id'));
            Redis::set('current_user:client_id',  $response->json('client_id'));
            Redis::set('current_user:token',  $response->json('token'));

            return response()->json([
                'success' => $response->json('success'),
                'message' => 'Successful!'
            ]);
        }

        return $response->json();
    }

    public function logout()
    {
        Redis::del('current_user:id');
        Redis::del('current_user:name');
        Redis::del('current_user:email');
        Redis::del('current_user:brand_id');
        Redis::del('current_user:client_id');
        Redis::del('current_user:token');

        return redirect('/');
    }
}
