<?php

namespace App\Http\Controllers\Incomes;

use App\Http\Controllers\Controller;
use App\Models\Incomes\Income;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserIncomeController extends Controller
{
    public function index(): View
    {
        return view('incomes.users.index');
    }

    public function create(Request $request): View
    {
        $copyFromId = $request->query('copy');

        if ($copyFromId === null) {
            return view('incomes.users.create', [
                'copyFromId' => null,
            ]);
        }

        $income = Income::findOrFail($copyFromId, ['id']);

        return view('incomes.users.create', [
            'copyFromId' => $income->id,
        ]);
    }

    public function show(int $id): View
    {
        $income = Income::findOrFail($id);

        return view('incomes.users.show', [
            'income' => $income,
        ]);
    }

    public function edit(int $id): View
    {
        $income = Income::findOrFail($id);

        return view('incomes.users.edit', [
            'income' => $income,
        ]);
    }
}
