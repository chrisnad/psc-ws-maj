<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
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
