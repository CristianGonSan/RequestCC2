<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\Contracts\View\View;

class ManagementRequestController extends Controller
{
    public function index(): View
    {
        return view('requests.management.index');
    }

    public function show(int $id): View
    {
        $requestModel = RequestModel::findOrFail($id);

        return view('requests.management.show', [
            'requestModel' => $requestModel,
        ]);
    }

    public function edit(int $id): View
    {
        $requestModel = RequestModel::findOrFail($id);

        abort_if(! $requestModel->status->isPending(), 403);

        return view('requests.management.edit', [
            'requestModel' => $requestModel,
        ]);
    }
}
