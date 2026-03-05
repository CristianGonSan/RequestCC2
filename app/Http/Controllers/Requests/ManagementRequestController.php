<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\RequestModel;
use App\Models\Type;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ManagementRequestController extends Controller
{
    public function index(): View
    {
        return view('requests.management.index');
    }

    public function show($id): View
    {
        $requestModel = RequestModel::findOrFail($id);
        return view('requests.management.show', compact('requestModel'));
    }

    public function edit($id): View
    {
        /** @var \App\Models\RequestModel $requestModel */
        $requestModel = RequestModel::findOrFail($id);

        if ($requestModel->status != RequestModel::STATUS_PENDING) {
            abort(403, 'Acción no autorizada.');
        }

        $types = Type::getEnabledTypes();
        $companies = Company::getEnabledCompaniesWithEnabledCostCenters();

        return view('requests.management.edit', compact([
            'requestModel',
            'companies',
            'types'
        ]));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        /** @var \App\Models\RequestModel $requestModel */
        $requestModel = RequestModel::findOrFail($id);

        if (!$requestModel->isPending()) {
            abort(403, 'Acción no autorizada.');
        }

        $request->merge([
            'amount' => str_replace(',', '', $request->input('amount', 0)), //1,000.00 => 1000.00
        ]);

        $rules = [
            'concept' => 'required|string',
            'cost_center' => 'required|string|max:128|exists:cost_centers,name',
            'payee' => 'required|string|max:128',
            'amount' => 'required|numeric',
            'type' => 'required|exists:types,key'
        ];

        if ($requestModel->is_transfer) {
            $rules = array_merge($rules, [
                'bank' => 'required|string|max:128',
                'card' => 'required|string|max:128',
                'account' => 'nullable|string|max:128',
                'branch' => 'nullable|string|max:128',
                'reference' => 'nullable|string|max:128',
                'covenant' => 'nullable|string|max:128',
            ]);

            $merge = [
                'card' => rtrim(str_replace('_', '', $request->input('card')), '-')
            ];

            if ($request->filled('account')) {
                $merge['account'] = rtrim(str_replace('_', '', $request->input('account')), '-');
            }

            $request->merge($merge);
        }

        $validated = $request->validate($rules);
        $requestModel->updateWithRecord($validated);

        return redirect()->route('management.requests.show', $id)
            ->with('success', 'Solicitud actualiza exitosamente.');
    }
}

