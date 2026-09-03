<?php

namespace App\Http\Controllers\MoneyRequests;

use App\Http\Controllers\Controller;
use App\Models\MoneyRequests\MoneyRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserMoneyRequestController extends Controller
{
    public function index(): View
    {
        return view('money-requests.users.index');
    }

    public function create(Request $request): View
    {
        $copyFromId = $request->query('copy');

        if ($copyFromId === null) {
            return view('money-requests.users.create', [
                'copyFromId' => null,
            ]);
        }

        $moneyRequest = MoneyRequest::findOrFail($copyFromId);

        abort_if(! $moneyRequest->isCurrentUser(), 403);

        return view('money-requests.users.create', [
            'copyFromId' => $moneyRequest->id,
        ]);
    }

    public function show(int $id): View
    {
        $moneyRequest = MoneyRequest::findOrFail($id);

        abort_if(! $moneyRequest->isCurrentUser(), 403);

        return view('money-requests.users.show', [
            'moneyRequest' => $moneyRequest,
        ]);
    }

    public function edit(int $id): View
    {
        $moneyRequest = MoneyRequest::findOrFail($id);

        abort_if(! $moneyRequest->isCurrentUser(), 403);

        abort_if(! $moneyRequest->status->isPending(), 403);

        return view('money-requests.users.edit', [
            'moneyRequest' => $moneyRequest,
        ]);
    }
}
