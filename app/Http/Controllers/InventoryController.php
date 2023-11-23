<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $current = [];
        $inventories = [];
        $items = [];
        $currencies = [
            'PHP' => '₱'
        ];

        $res = Http::withHeaders([
                    'Accept' => 'application/json'
                ])->replaceHeaders([
                    'Content-Type' => 'application/json'
                ])->withToken($this->user->token)
                ->get(env('ENDPOINT_HOST') . '/api/inventories');

        if ($res->json('success') !== null && $res->json('success')) {
            $last_page = $res->json('data')['last_page'];

            $current_page = 1;

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

            foreach ($inventories as $key => $value) {
                $itemId = $value["item_id"];

                if (isset($items[$itemId])) {
                    if ($value["operator"] == "+") {
                        $items[$itemId]['qty'] += $value["qty"];
                    } else if ($value["operator"] == "-") {
                        $items[$itemId]['qty'] -= $value["qty"];
                        $items[$itemId]['price'] = $value["item"]["price"] / $value["qty"];
                    }
                } else {
                    $items[$itemId]['name'] = $value["item"]["name"];
                    $items[$itemId]['item_id'] = $value["item_id"];
                    $items[$itemId]['item_code'] = $value["item"]["item_code"];
                    $items[$itemId]['orig_qty'] = $value["qty"];
                    $items[$itemId]['qty'] = $value["qty"];
                    $items[$itemId]['unit'] = $value["unit"];
                    $items[$itemId]['currency'] = $value["item"]["currency"];
                    $items[$itemId]['price'] = $value["item"]["price"];
                    $items[$itemId]['created_at'] = $value["created_at"];
                    $items[$itemId]['expired_at'] = $value["item"]["expired_at"];
                } 
            }
        }

        return view('sections.inventory.index', [
            'current' => json_encode(array_values($current)),
            'transactions' => json_encode($inventories),
            'items' => json_encode(array_values($items)),
            'currencies' => $currencies
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->action == "NewStock") {
            $params = [
                "user_id" => $this->user->id,
                "name" => ucfirst($request->name),
                "item_code" => Str::random(10),
                "currency" => $request->currency,
                "price" => $request->price,
                "expired_at" => $request->expired_at
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

        if ($request->action == "Destock") {
            unset($params);

            $params = [
                "item_id" => $request->item_id,
                "qty" => $request->qty,
                "unit" => ucfirst($request->unit),
                "operator" => "-"
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
