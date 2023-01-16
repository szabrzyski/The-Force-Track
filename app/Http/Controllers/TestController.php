<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Test method for development purposes.
     *
     * @param  request  $request
     * @return mixed
     */
    public function test(Request $request)
    {
        return true;
    }
}
