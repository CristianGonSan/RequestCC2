<?php

namespace App\Http\Controllers\MoneyRequests;

use App\Http\Controllers\Controller;
use App\Models\MoneyRequests\MoneyRequest;
use Illuminate\Contracts\View\View;

class ManagementMoneyRequestController extends Controller
{
    public function index(): View
    {
        return view('money-requests.management.index');
    }

    public function show(int $id): View
    {
        $moneyRequest = MoneyRequest::findOrFail($id);

        return view('money-requests.management.show', [
            'moneyRequest' => $moneyRequest,
        ]);
    }

    public function edit(int $id): View
    {
        $moneyRequest = MoneyRequest::findOrFail($id);

        abort_if(! $moneyRequest->status->isPending(), 403);

        return view('money-requests.management.edit', [
            'moneyRequest' => $moneyRequest,
        ]);
    }
}
