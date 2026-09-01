<?php

namespace App\Http\Controllers\MaterialRequests;

use App\Http\Controllers\Controller;
use App\Models\MaterialRequests\MaterialRequest;
use Illuminate\Contracts\View\View;

class FulfillmentMaterialRequestController extends Controller
{
    public function index(): View
    {
        return view('material-requests.fulfillment.index');
    }

    public function show(int $id): View
    {
        $materialRequest = MaterialRequest::findOrFail($id);

        $this->authorize('view', $materialRequest);

        return view('material-requests.fulfillment.show', [
            'materialRequest' => $materialRequest,
        ]);
    }
}
