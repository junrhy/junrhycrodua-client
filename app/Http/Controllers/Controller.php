<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Redis;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $user;

    public function __construct()
    {
        $this->user = (object) [
            'id' => Redis::get('current_user:id'),
            'name' => Redis::get('current_user:name'),
            'token' => Redis::get('current_user:token')
        ];

        view()->share('user', $this->user);
    }
}
