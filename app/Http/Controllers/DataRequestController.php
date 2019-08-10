<?php

namespace Hydrofon\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DataRequestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Generate new file with user data.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->streamDownload(function () use ($request) {
            echo $request->user()->exportToJson();
        }, 'hydrofon-'.Str::slug($request->user()->name).'.json', ['Content-Type' => 'application/json; charset=UTF-8']);
    }
}
