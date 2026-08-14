<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use Auth;
use Illuminate\Contracts\Support\Renderable;

class DashboardController extends Controller
{
    public function index(): Renderable
    {
        return view('dashboard');
    }
}
