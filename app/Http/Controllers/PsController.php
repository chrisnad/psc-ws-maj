<?php

namespace App\Http\Controllers;

use App\Models\Ps;
use App\Psc\Transformers\ExpertiseTransformer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $this->psTransformer = new ExpertiseTransformer();
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
     * @return Application|Factory|View|RedirectResponse
     */
    public function getById(Request $request)
    {
        request()->validate([
            'id' => 'required'
        ]);

        $id = $request->input('id');
        // TODO: GET THE ps THE RIGHT WAY
        try {
            $ps = Ps::findOrFail($id);
        } catch(ModelNotFoundException $e) {
            return view('welcome', [
                'title' => 'Erreur',
                'message' => "Cet identifiant n'existe pas"
            ]);
        }

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
            return view('welcome', [
                'title' => 'Info',
                'message' => "Aucun changement n'a été éffectué"
            ]);
        }

        // TODO: this updates in our database, adapt for WS
        $ps->update(array_filter(request()->all()));

        Log::info("authenticated_user=".Auth::user()->preferred_username." modified_user=".$ps->nationalId);

        return view('welcome', [
            'title' => 'Succès',
            'message' => 'Les modifications on bien été pris en compte'
        ]);
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
