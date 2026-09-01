<?php

namespace App\Http\Controllers\MaterialRequests;

use App\Http\Controllers\Controller;
use App\Models\MaterialRequests\MaterialRequest;
use Illuminate\Contracts\View\View;

class UserMaterialRequestController extends Controller
{
    public function index(): View
    {
        return view('material-requests.users.index');
    }

    public function create(): View
    {
        return view('material-requests.users.create');
    }

    public function show(int $id): View
    {
        $materialRequest = MaterialRequest::findOrFail($id);

        $this->authorize('view', $materialRequest);

        return view('material-requests.users.show', [
            'materialRequest' => $materialRequest,
        ]);
    }

    public function edit(int $id): View
    {
        $materialRequest = MaterialRequest::findOrFail($id);

        $this->authorize('update', $materialRequest);

        return view('material-requests.users.edit', [
            'materialRequest' => $materialRequest,
        ]);
    }
}
