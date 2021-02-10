<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

class WelcomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('welcome', [
            'title' => request()->title,
            'message' => request()->message
        ]);
    }
}
