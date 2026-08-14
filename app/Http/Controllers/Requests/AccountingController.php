<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\Contracts\View\View;

class AccountingController extends Controller
{
    public function index(): View
    {
        return view('requests.accounting.index');
    }

    public function show(int $id): View
    {
        $requestModel = RequestModel::findOrFail($id);

        return view('requests.accounting.show', [
            'requestModel' => $requestModel,
        ]);
    }
}
