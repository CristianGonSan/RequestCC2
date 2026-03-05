<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Controllers\MailManager;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        return view('admin.users.index');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $password = $validated['password'];

        if ($request->boolean('send_email')) {
            MailManager::sendNewUserNotification($user, $password);
        }

        if ($request->boolean('redirect_to_show')) {
            return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Usuario creado correctamente.');
        } else {
            return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente.');
        }
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }
}
