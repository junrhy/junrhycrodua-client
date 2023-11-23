<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = [];
        $pendings = [];
        $completed = [];

        $res = Http::withHeaders([
                    'Accept' => 'application/json'
                ])->replaceHeaders([
                    'Content-Type' => 'application/json'
                ])->withToken($this->user->token)
                ->get(env('ENDPOINT_HOST') . '/api/orders');

        if ($res->json('success') !== null && $res->json('success')) {
            $last_page = $res->json('data')['last_page'];

            $current_page = 1;

            while ($current_page < $last_page + 1) {
                $response = Http::withHeaders([
                            'Accept' => 'application/json'
                        ])->replaceHeaders([
                            'Content-Type' => 'application/json'
                        ])->withToken($this->user->token)
                        ->get(env('ENDPOINT_HOST') . '/api/orders?page=' . $current_page);

                foreach ($response->json('data')['data'] as $key => $value) {
                    array_push($sales, $value);
                }

                $current_page++;
            }

            foreach ($sales as $key => $value) {
                $itemName = $value["item"]["name"];

                // $pendings[$itemName]['name'] = $itemName;
                // $pendings[$itemName]['qty'] = $value["item"]["qty"];
                // $pendings[$itemName]['unit'] = $value["item"]["unit"];
                // $pendings[$itemName]['price'] = $value["item"]["price"];
                // $pendings[$itemName]['amount'] = $value["amount"];
            }
        }

        return view('sections.order.index', [
            'orders' => $orders,
            'pendings' => json_encode($pendings),
            'completed' => json_encode($completed)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
