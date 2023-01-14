<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SanctumTestController extends Controller
{
    public function index()
    {
        $user_info = auth('sanctum')->user()->email;
        return $user_info;
    }
}
