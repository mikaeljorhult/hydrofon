<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataRequestController extends Controller
{
    /**
     * Generate new file with user data.
     */
    public function store(Request $request): StreamedResponse
    {
        return response()->streamDownload(function () use ($request) {
            echo $request->user()->exportToJson();
        }, 'hydrofon-'.Str::slug($request->user()->name).'.json',
            ['Content-Type' => 'application/json; charset=UTF-8']);
    }
}
