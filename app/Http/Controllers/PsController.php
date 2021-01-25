<?php

namespace App\Http\Controllers;

use App\Models\Ps;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PsController extends Controller
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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Ps $ps
     * @return Application|Factory|View
     */
    public function show(Ps $ps)
    {
        return view('ps.show', ['ps' => $ps]);
    }

    /**
     * Display the specified resource.
     * @param Request $request
     * @return RedirectResponse
     */
    public function getById(Request $request): RedirectResponse
    {
        request()->validate([
            'id' => 'required'
        ]);

        $id = $request->input('id');
        // TODO: GET THE ps THE RIGHT WAY
        $ps = Ps::findOrFail($id);

        return redirect()->route('ps.show', $ps);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $ps
     * @return Application|Factory|View
     */
    public function edit(Ps $ps)
    {
        // TODO: GET THE ps THE RIGHT WAY
        //$ps = Ps::findOrFail($ps);

        return view('ps.edit', ['ps' => $ps]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Ps $ps
     * @return Application|Factory|View
     */
    public function update(Ps $ps)
    {
        // TODO: this updates in our database, adapt for WS
        $ps->update($this->validatePs());

        $message = "L'utilisateur ".Auth::user()->preferred_username." a modifié l'utilisateur ".$ps->nationalId;
        Log::info($message);

        return view('welcome', ['message' => $message]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Ps $ps
     * @return Response
     */
    public function destroy(Ps $ps): Response
    {
        //
    }

    /**
     * Validate Ps.
     *
     * @return array
     */
    protected function validatePs(): array
    {
        return request()->validate([
            'email'      => 'required',
            'phone'      => 'required',
            'conditions' => 'accepted'
        ]);
    }
}
