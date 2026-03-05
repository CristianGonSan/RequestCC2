<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MailManager;
use App\Models\RequestModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRequestController extends Controller
{
    public function index(): View
    {
        return view('requests.user.index');
    }

    public function create(): View
    {
        $user = Auth::user();
        $types = $user->getTypesEnabled();
        $companies = $user->getEnabledCompaniesWithEnabledCostCenters();

        return view('requests.user.create', compact([
            'types',
            'companies'
        ]));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'amount' => str_replace(',', '', $request->input('amount', 0)), //1,000.00 => 1000.00
        ]);

        $rules = [
            'concept' => 'required|string',
            'cost_center' => 'required|string|max:128|exists:cost_centers,name',
            'payee' => 'required|string|max:128',
            'amount' => 'required|numeric',
            'type' => 'required|exists:types,key',

            'is_transfer' => 'required|bool',
        ];

        if ($request->boolean('is_transfer')) {
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
        $validated['user_id'] = Auth::id();

        $requestModel = RequestModel::create($validated);
        MailManager::sendNewRequestNotification($requestModel);

        return redirect()->route('requests.show', $requestModel->id)
            ->with('success', 'Solicitud creada exitosamente.');
    }

    public function show($id): View
    {
        $requestModel = findUserRequestOrFail($id);

        return view('requests.user.show', compact([
            'requestModel'
        ]));
    }

    public function edit($id): View
    {
        /** @var \App\Models\RequestModel $requestModel */
        $requestModel = findUserRequestOrFail($id);

        if (!$requestModel->isPending()) {
            abort(403, 'Acción no autorizada.');
        }

        $user = Auth::user();
        $types = $user->getTypesEnabled();
        $companies = $user->getEnabledCompaniesWithEnabledCostCenters();

        return view('requests.user.edit', compact([
            'requestModel',
            'companies',
            'types'
        ]));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        /** @var \App\Models\RequestModel $requestModel */
        $requestModel = findUserRequestOrFail($id);

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

        return redirect()->route('requests.show', $id)
            ->with('success', 'Solicitud actualiza exitosamente.');
    }

    public function copy($id): View
    {
        $requestModel = findUserRequestOrFail($id);

        $user = Auth::user();
        $types = $user->getTypesEnabled();
        $companies = $user->getEnabledCompaniesWithEnabledCostCenters();

        return view('requests.copy', compact([
            'requestModel',
            'types',
            'companies'
        ]));
    }

    public function destroy($id): RedirectResponse
    {
        $requestModel = findUserRequestOrFail($id);

        if (!$requestModel->isPending()) {
            abort(403, 'Acción no autorizada.');
        }

        $requestModel->delete();

        return redirect()->route('requests.index')
            ->with('success', 'Solicitud eliminada exitosamente.');
    }
}

function findUserRequestOrFail($id): RequestModel
{
    $requestModel = RequestModel::findOrFail($id);

    if ($requestModel->user_id !== Auth::id()) {
        abort(403, 'Acción no autorizada.');
    }

    return $requestModel;
}
