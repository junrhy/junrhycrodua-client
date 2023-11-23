<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = [];
        $currencies = [
            'PHP' => '₱'
        ];

        $res = Http::withHeaders([
                    'Accept' => 'application/json'
                ])->replaceHeaders([
                    'Content-Type' => 'application/json'
                ])->withToken($this->user->token)
                ->get(env('ENDPOINT_HOST') . '/api/services');

        if ($res->json('success') !== null && $res->json('success')) {
            $last_page = $res->json('data')['last_page'];

            $current_page = 1;

            while ($current_page < $last_page + 1) {
                $response = Http::withHeaders([
                            'Accept' => 'application/json'
                        ])->replaceHeaders([
                            'Content-Type' => 'application/json'
                        ])->withToken($this->user->token)
                        ->get(env('ENDPOINT_HOST') . '/api/services?page=' . $current_page);

                foreach ($response->json('data')['data'] as $key => $value) {
                    array_push($services, $value);
                }

                $current_page++;
            }
        }

        return view('sections.service.index', [
            'services' => json_encode($services),
            'currencies' => $currencies
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = [
            "user_id" => $this->user->id,
            "long_name" => ucfirst($request->long_name),
            "short_name" => ucfirst($request->short_name),
            "category" => ucfirst($request->category)
        ];

        $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])->replaceHeaders([
                'Content-Type' => 'application/json'
            ])->withBody(
                json_encode($params)
            )->withToken($this->user->token)
            ->post(env('ENDPOINT_HOST') . '/api/services');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
