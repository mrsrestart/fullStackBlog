<?php

namespace App\Http\Controllers;


class TestController extends Controller
{
    public function controllerMethod()
    {
        return response()->json([
            'msg' => 'We Should return Only json'
        ]);
    }

    public function test()
    {
        return "This is first Tag";
    }
}
