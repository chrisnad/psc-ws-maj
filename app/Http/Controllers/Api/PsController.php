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
        $psList = Ps::all()->toArray();
        return $this->respond([
            'data' => $this->psTransformer->transformCollection($psList)
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
        if (! request()->input('phone') or ! request()->input('email')){
            return $this->setStatusCode(400)->respondWithError('Parameters failed validation for a Ps');
        }

        // TODO: this updates in our database, adapt for WS
        $ps->update($this->validatePs());

        return $this->respond([
            'message' => 'Ps updated.'
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

    /**
     * Validate Ps.
     *
     * @return array
     */
    protected function validatePs(): array
    {
        return request()->validate([
            'email'      => 'required',
            'phone'      => 'required'
        ]);
    }
}
