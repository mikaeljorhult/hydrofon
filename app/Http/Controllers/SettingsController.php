<?php

namespace App\Http\Controllers;

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
        ]);
    }

    public function update(General $settings, Request $request)
    {
        $validated = $request->validate([
            'require_approval' => ['required', Rule::in(['none', 'all', 'equipment', 'facilities'])],
        ]);

        $settings->require_approval = $validated['require_approval'];
        $settings->save();

        return redirect()->route('settings.index')->with('success', 'Settings saved');
    }
}
