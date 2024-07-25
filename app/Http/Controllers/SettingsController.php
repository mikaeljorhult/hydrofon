<?php

namespace App\Http\Controllers;

use App\Enums\ApprovalSetting;
use App\Settings\General;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(General $settings)
    {
        return view('settings.index', [
            'require_approval' => $settings->require_approval,
            'desk_inclusion_earlier' => $settings->desk_inclusion_earlier,
            'desk_inclusion_later' => $settings->desk_inclusion_later,
        ]);
    }

    public function update(General $settings, Request $request)
    {
        $validated = $request->validate([
            'require_approval' => ['required', Rule::enum(ApprovalSetting::class)],
            'desk_inclusion_earlier' => ['required', 'integer', 'min:0', 'max:240'],
            'desk_inclusion_later' => ['required', 'integer', 'min:0', 'max:240'],
        ]);

        $settings->require_approval = $validated['require_approval'];
        $settings->desk_inclusion_earlier = $validated['desk_inclusion_earlier'];
        $settings->desk_inclusion_later = $validated['desk_inclusion_later'];
        $settings->save();

        return redirect()->route('settings.index')->with('success', 'Settings saved');
    }
}
