<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('welcome', [
            'content' => file_get_contents(__DIR__ . '/../../../README.md'),
        ]);
    }
}
