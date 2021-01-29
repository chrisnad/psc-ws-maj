<?php

namespace App\Http\Controllers\Api;

use App\Models\Ps;
use App\Psc\Transformers\PsTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

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
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
//        if (! request()->input('phone') and ! request()->input('email')){
//            return $this->setStatusCode(400)->respondWithError('Erreur au niveau des paramètres renseignés');
//        }

        $ps = request()->all();
        $ps['nationalId'] = (string) Str::uuid();

        Ps::create($ps);

        return $this->respond([
            'message' => 'Creation avec succès.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        try {
            $ps = Ps::findOrFail($id);
        } catch(ModelNotFoundException $e) {
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
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        try {
            $ps = Ps::findOrFail($id);
        } catch(ModelNotFoundException $e) {
            return $this->responseNotFound("Ce professionel n'exist pas.");
        }
        $ps->delete();
        return $this->respond([
            'message' => 'Supression du Ps avec succès.'
        ]);
    }

}
