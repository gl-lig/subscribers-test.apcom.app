<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Models\UserGroup;
use App\Services\AdminLogService;
use Illuminate\Http\Request;

class AdminPromoCodeController extends Controller
{
    public function index()
    {
        $promoCodes = PromoCode::with('userGroup')->latest()->paginate(20);
        return view('admin.promo-codes.index', compact('promoCodes'));
    }

    public function create()
    {
        $groups = UserGroup::all();
        return view('admin.promo-codes.form', ['promoCode' => null, 'groups' => $groups]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:100|unique:promo_codes',
            'discount_pct' => 'required|numeric|min:0|max:100',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'usage_limit_per_user' => 'required|integer|min:1',
            'scope' => 'required|in:all,specific_user,group',
            'bat_id_specific' => 'nullable|required_if:scope,specific_user|string',
            'user_group_id' => 'nullable|required_if:scope,group|exists:user_groups,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $promo = PromoCode::create($validated);

        AdminLogService::log('create', 'promo_codes', null, $promo->toArray());

        return redirect()->route('admin.promo-codes.index')->with('success', 'Code promo créé.');
    }

    public function edit(PromoCode $promoCode)
    {
        $groups = UserGroup::all();
        return view('admin.promo-codes.form', compact('promoCode', 'groups'));
    }

    public function update(Request $request, PromoCode $promoCode)
    {
        $before = $promoCode->toArray();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:100|unique:promo_codes,code,' . $promoCode->id,
            'discount_pct' => 'required|numeric|min:0|max:100',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'usage_limit_per_user' => 'required|integer|min:1',
            'scope' => 'required|in:all,specific_user,group',
            'bat_id_specific' => 'nullable|required_if:scope,specific_user|string',
            'user_group_id' => 'nullable|required_if:scope,group|exists:user_groups,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $promoCode->update($validated);

        AdminLogService::log('update', 'promo_codes', $before, $promoCode->fresh()->toArray());

        return redirect()->route('admin.promo-codes.index')->with('success', 'Code promo mis à jour.');
    }

    public function destroy(PromoCode $promoCode)
    {
        AdminLogService::log('delete', 'promo_codes', $promoCode->toArray());
        $promoCode->delete();

        return redirect()->route('admin.promo-codes.index')->with('success', 'Code promo supprimé.');
    }
}
