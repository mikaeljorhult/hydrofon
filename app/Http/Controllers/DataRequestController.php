<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

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
     */
    public function store(Request $request): Response
    {
        return response()->streamDownload(function () use ($request) {
            echo $request->user()->exportToJson();
        }, 'hydrofon-'.Str::slug($request->user()->name).'.json',
            ['Content-Type' => 'application/json; charset=UTF-8']);
    }
}
