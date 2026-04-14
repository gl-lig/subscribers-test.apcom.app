<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\AdminLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAdminController extends Controller
{
    public function index()
    {
        $admins = Admin::latest()->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.form', ['admin' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin',
            'notify_new_order' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 'active';
        $validated['notify_new_order'] = $request->boolean('notify_new_order');

        $admin = Admin::create($validated);
        AdminLogService::log('create', 'admins', null, ['email' => $admin->email, 'role' => $admin->role]);

        return redirect()->route('admin.admins.index')->with('success', 'Administrateur créé.');
    }

    public function edit(Admin $admin)
    {
        return view('admin.admins.form', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $before = $admin->toArray();

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'role' => 'required|in:super_admin,admin',
            'status' => 'required|in:active,inactive',
            'notify_new_order' => 'boolean',
        ]);

        // Prevent removing last super_admin
        if ($admin->isSuper() && $validated['role'] !== 'super_admin') {
            $superCount = Admin::active()->where('role', 'super_admin')->count();
            if ($superCount <= 1) {
                return back()->withErrors(['role' => 'Impossible : au moins 1 super administrateur actif requis.']);
            }
        }

        if ($admin->isSuper() && $validated['status'] === 'inactive') {
            $superCount = Admin::active()->where('role', 'super_admin')->count();
            if ($superCount <= 1) {
                return back()->withErrors(['status' => 'Impossible : au moins 1 super administrateur actif requis.']);
            }
        }

        $validated['notify_new_order'] = $request->boolean('notify_new_order');

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $validated['password'] = Hash::make($request->input('password'));
        }

        $admin->update($validated);
        AdminLogService::log('update', 'admins', $before, $admin->fresh()->toArray());

        return redirect()->route('admin.admins.index')->with('success', 'Administrateur mis à jour.');
    }

    public function destroy(Admin $admin)
    {
        if ($admin->isSuper()) {
            $superCount = Admin::active()->where('role', 'super_admin')->count();
            if ($superCount <= 1) {
                return back()->withErrors(['error' => 'Impossible de supprimer le dernier super administrateur.']);
            }
        }

        AdminLogService::log('delete', 'admins', $admin->toArray());
        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Administrateur supprimé.');
    }
}
