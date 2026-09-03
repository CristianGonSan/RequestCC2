<?php

namespace App\Http\Controllers\MoneyRequests;

use App\Http\Controllers\Controller;
use App\Models\MoneyRequests\MoneyRequest;
use Illuminate\Contracts\View\View;

class AccountingMoneyRequestController extends Controller
{
    public function index(): View
    {
        return view('money-requests.accounting.index');
    }

    public function show(int $id): View
    {
        $moneyRequest = MoneyRequest::findOrFail($id);

        return view('money-requests.accounting.show', [
            'moneyRequest' => $moneyRequest,
        ]);
    }
}
