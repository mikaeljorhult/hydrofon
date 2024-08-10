<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return auth()->check()
            ? redirect('calendar')
            : view('welcome');
    }
}
