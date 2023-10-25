<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $template = $this->setTemplate($request);

        return view($template, [
            'user' => 'Test User Variable'
        ]);
    }

    private function setTemplate($request)
    {
        $template = 'templates.default';

        if (isset($request->t)) {
            $template = $request->t;
        }

        return $template;
    }
}
