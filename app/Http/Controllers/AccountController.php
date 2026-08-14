<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function show(): View {
        return view('account.show', [
            'user' => Auth::user()
        ]);
    }
}
