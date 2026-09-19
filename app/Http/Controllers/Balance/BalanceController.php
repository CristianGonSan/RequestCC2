<?php

namespace App\Http\Controllers\Balance;

use App\Http\Controllers\Controller;

class BalanceController extends Controller
{
    public function index()
    {
        return view('balance.index');
    }
}
