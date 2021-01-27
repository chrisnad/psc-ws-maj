<?php

namespace App\Http\Controllers\Api;

use App\Models\Ps;
use App\Psc\Transformers\PsTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PsController extends ApiController
{

    protected $psTransformer;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->psTransformer = new PsTransformer();
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $psList = Ps::all();
        return $this->respond([
            'data' => $this->psTransformer->transformCollection($psList->all())
        ]);
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
     * @return mixed
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        // TODO: this updates in our database, adapt for WS
        $ps = Ps::find($id);
        if (!$ps){
            return $this->responseNotFound("Ce professionel n'exist pas.");
        }

        return $this->respond([
            'data' => $this->psTransformer->transform($ps)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Ps $ps
     * @return mixed
     */
    public function update(Ps $ps)
    {
        if (! request()->input('phone') and ! request()->input('email')){
            return $this->setStatusCode(400)->respondWithError('Erreur au niveau des paramètres renseignés');
        }

        // TODO: this updates in our database, adapt for WS
        $ps->update(array_filter(request()->all()));

        return $this->respond([
            'message' => 'Mise à jour du Ps avec succès.'
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
