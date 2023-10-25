<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $redis = Redis::connection();

        //STRING
        $redis->set('name', 'Taylor');
        $name = $redis->get('name');

        //  LIST
        //  A list is a series of ordered values. Some of the important commands for interacting with lists are RPUSH, LPUSH, LLEN, LRANGE, LPOP, and RPOP.
        $redis->rpush('friends', 'alice');
        $redis->rpush('friends', 'tom');
        $redis->lpush('friends', 'bob');
        $dosprimeros = $redis->lrange('friends', 0,1);
        $todos = $redis->lrange('friends', 0,-1);
        $cuantos = $redis->llen('friends');

        dd($todos);

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
                    )->post(env('LOGIN_ENDPOINT'));

        if ($response->json('success')) {


            return response()->json([
                'success' => $response->json('success'),
                'message' => 'Successful!'
            ]);
        }

        return $response->json();
    }
}
