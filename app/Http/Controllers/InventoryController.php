<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $res = Http::withHeaders([
                    'Accept' => 'application/json'
                ])->replaceHeaders([
                    'Content-Type' => 'application/json'
                ])->withToken($this->user->token)
                ->get(env('ENDPOINT_HOST') . '/api/inventories');

        $last_page = $res->json('data')['last_page'];

        $current_page = 1;
        $inventories = [];

        while ($current_page < $last_page + 1) {
            $response = Http::withHeaders([
                        'Accept' => 'application/json'
                    ])->replaceHeaders([
                        'Content-Type' => 'application/json'
                    ])->withToken($this->user->token)
                    ->get(env('ENDPOINT_HOST') . '/api/inventories?page=' . $current_page);

            foreach ($response->json('data')['data'] as $key => $value) {
                array_push($inventories, $value);
            }

            $current_page++;
        }

        $current = [];
        foreach ($inventories as $key => $value) {
            $itemName = $value["item"]["name"];

            if (isset($current[$itemName])) {
                if ($value["operator"] == "+") {
                    $current[$itemName]['qty'] += $value["qty"];
                } else if ($value["operator"] == "-") {
                    $current[$itemName]['qty'] -= $value["qty"];
                }
            } else {
                $current[$itemName]['name'] = $value["item"]["name"];
                $current[$itemName]['qty'] = $value["qty"];
                $current[$itemName]['unit'] = $value["unit"];
            }            
        }

        return view('sections.inventory.index', [
            'current' => json_encode(array_values($current)),
            'transactions' => json_encode($inventories)
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = [
            "user_id" => $this->user->id,
            "name" => ucfirst($request->name)
        ];

        $response = Http::withHeaders([
                        'Accept' => 'application/json'
                    ])->replaceHeaders([
                        'Content-Type' => 'application/json'
                    ])->withBody(
                        json_encode($params)
                    )->withToken($this->user->token)
                    ->post(env('ENDPOINT_HOST') . '/api/items');

        if ($response->json('success')) {
            unset($params);

            $params = [
                "item_id" => $response->json('data')['id'],
                "qty" => $request->qty,
                "unit" => ucfirst($request->unit),
                "operator" => "+"
            ];

            unset($response);
            $response = Http::withHeaders([
                        'Accept' => 'application/json'
                    ])->replaceHeaders([
                        'Content-Type' => 'application/json'
                    ])->withBody(
                        json_encode($params)
                    )->withToken($this->user->token)
                    ->post(env('ENDPOINT_HOST') . '/api/inventories');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = Http::withHeaders([
                        'Accept' => 'application/json'
                    ])->replaceHeaders([
                        'Content-Type' => 'application/json'
                    ])->withToken($this->user->token)
                    ->delete(env('ENDPOINT_HOST') . '/api/inventories/' . $id);
    }
}
