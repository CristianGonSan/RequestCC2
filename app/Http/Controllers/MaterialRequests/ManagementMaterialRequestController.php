<?php

namespace App\Http\Controllers\MaterialRequests;

use App\Http\Controllers\Controller;
use App\Models\MaterialRequests\MaterialRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ManagementMaterialRequestController extends Controller
{
    public function index(): View
    {
        return view('material-requests.management.index');
    }

    public function show(int $id): View
    {
        $materialRequest = MaterialRequest::findOrFail($id);

        $this->authorize('view', $materialRequest);

        return view('material-requests.management.show', [
            'materialRequest' => $materialRequest,
        ]);
    }
}
