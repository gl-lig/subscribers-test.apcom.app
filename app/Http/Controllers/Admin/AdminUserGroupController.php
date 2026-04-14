<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserGroup;
use App\Services\AdminLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminUserGroupController extends Controller
{
    public function index()
    {
        $groups = UserGroup::withCount([
            'promoCodes',
        ])->get()->map(function ($group) {
            $group->member_count = DB::table('user_group_members')->where('group_id', $group->id)->count();
            return $group;
        });

        return view('admin.user-groups.index', compact('groups'));
    }

    public function create()
    {
        return view('admin.user-groups.form', ['group' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $group = UserGroup::create($validated);
        AdminLogService::log('create', 'user_groups', null, $group->toArray());

        return redirect()->route('admin.user-groups.index')->with('success', 'Groupe créé.');
    }

    public function show(UserGroup $userGroup)
    {
        $members = DB::table('user_group_members')
            ->where('group_id', $userGroup->id)
            ->paginate(20);

        return view('admin.user-groups.form', ['group' => $userGroup, 'members' => $members]);
    }

    public function edit(UserGroup $userGroup)
    {
        $members = DB::table('user_group_members')
            ->where('group_id', $userGroup->id)
            ->paginate(20);

        return view('admin.user-groups.form', ['group' => $userGroup, 'members' => $members]);
    }

    public function update(Request $request, UserGroup $userGroup)
    {
        $before = $userGroup->toArray();
        $userGroup->update($request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]));

        AdminLogService::log('update', 'user_groups', $before, $userGroup->fresh()->toArray());

        return redirect()->route('admin.user-groups.index')->with('success', 'Groupe mis à jour.');
    }

    public function destroy(UserGroup $userGroup)
    {
        AdminLogService::log('delete', 'user_groups', $userGroup->toArray());
        $userGroup->delete();

        return redirect()->route('admin.user-groups.index')->with('success', 'Groupe supprimé.');
    }

    public function addMember(Request $request, UserGroup $userGroup)
    {
        $request->validate(['bat_id' => 'required|string|max:100']);
        $userGroup->addMember($request->input('bat_id'));

        return back()->with('success', 'Membre ajouté.');
    }

    public function removeMember(UserGroup $userGroup, string $batId)
    {
        $userGroup->removeMember($batId);
        return back()->with('success', 'Membre retiré.');
    }
}
