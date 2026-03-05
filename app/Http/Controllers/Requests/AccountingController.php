<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class AccountingController extends Controller
{
    public function index(): Application|Factory|View
    {
        return view('requests.accounting.index');
    }

    public function show($id): Application|Factory|View
    {
        $requestModel = RequestModel::findOrFail($id);
        return view('requests.accounting.show', compact('requestModel'));
    }
}

