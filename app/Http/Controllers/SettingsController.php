<?php

namespace App\Http\Controllers;

use App\Enums\ApprovalSetting;
use App\Settings\General;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index(General $settings)
    {
        return view('settings.index', [
            'require_approval' => $settings->require_approval,
            'desk_inclusion_earlier' => $settings->desk_inclusion_earlier,
            'desk_inclusion_later' => $settings->desk_inclusion_later,
            'prune_bookings' => $settings->prune_bookings,
            'prune_users' => $settings->prune_users,
        ]);
    }

    public function update(General $settings, Request $request)
    {
        $validated = $request->validate([
            'require_approval' => ['required', Rule::enum(ApprovalSetting::class)],
            'desk_inclusion_earlier' => ['required', 'integer', 'min:0', 'max:240'],
            'desk_inclusion_later' => ['required', 'integer', 'min:0', 'max:240'],
            'prune_bookings' => ['required', 'integer', 'min:0', 'max:3650'],
            'prune_users' => ['required', 'integer', 'min:0', 'max:3650'],
        ]);

        $settings->require_approval = $validated['require_approval'];
        $settings->desk_inclusion_earlier = $validated['desk_inclusion_earlier'];
        $settings->desk_inclusion_later = $validated['desk_inclusion_later'];
        $settings->prune_bookings = $validated['prune_bookings'];
        $settings->prune_users = $validated['prune_users'];
        $settings->save();

        return redirect()->route('settings.index')->with('success', 'Settings saved');
    }
}
