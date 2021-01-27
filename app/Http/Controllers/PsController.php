<?php

namespace App\Http\Controllers;

use App\Models\Ps;
use App\Psc\Transformers\PsTransformer;
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

    protected $psTransformer;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->psTransformer = new PsTransformer();
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
     * Search for the specified resource.
     *
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

        return redirect()->route('ps.show', $ps['nationalId']);
    }

    /**
     * Display the specified resource.
     *
     * @param Ps $ps
     * @return Application|Factory|View
     */
    public function show(Ps $ps)
    {
        return view('ps.show', ['ps' => $this->psTransformer->transform($ps)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $ps
     * @return Application|Factory|View
     */
    public function edit(Ps $ps)
    {
        return view('ps.edit', [
            'ps' => $this->psTransformer->transform($ps)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Ps $ps
     * @return array|Application|Factory|View
     */
    public function update(Ps $ps)
    {
        request()->validate([
            'conditions' => 'accepted'
        ]);

        if (! request()->input('phone') and ! request()->input('email')){
            return view('welcome', ['message' => "Aucun changement n'a été éffectué"]);
        }

        // TODO: this updates in our database, adapt for WS
        $ps->update(array_filter(request()->all()));

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

}
