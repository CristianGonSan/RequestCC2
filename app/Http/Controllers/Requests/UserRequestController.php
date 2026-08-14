<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserRequestController extends Controller
{
    public function index(): View
    {
        return view('requests.users.index');
    }

    public function create(Request $request): View
    {
        $copyFromId = $request->query('copy');

        if ($copyFromId === null) {
            return view('requests.users.create', [
                'copyFromId' => null,
            ]);
        }

        $requestModel = RequestModel::findOrFail($copyFromId);

        abort_if(! $requestModel->isCurrentUser(), 403);

        return view('requests.users.create', [
            'copyFromId' => $requestModel->id,
        ]);
    }

    public function show(int $id): View
    {
        $requestModel = RequestModel::findOrFail($id);

        abort_if(! $requestModel->isCurrentUser(), 403);

        return view('requests.users.show', [
            'requestModel' => $requestModel,
        ]);
    }

    public function edit(int $id): View
    {
        $requestModel = RequestModel::findOrFail($id);

        abort_if(! $requestModel->isCurrentUser(), 403);

        abort_if(! $requestModel->status->isPending(), 403);

        return view('requests.users.edit', [
            'requestModel' => $requestModel,
        ]);
    }
}
