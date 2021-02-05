<?php

namespace App\Http\Controllers\Api;

use App\Models\Ps;

use Exception;
use Illuminate\Support\Str;

class PsController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $psList = Ps::all();
        return $this->successResponse($this->psTransformer->transformCollection($psList->all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
        $ps = array_filter(request()->all());
        $ps['nationalId'] = (string) Str::uuid();

        Ps::create($ps);
        return $this->successResponse(null, 'Creation avec succès');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $ps = $this->getPs($id);
        return $this->successResponse($this->psTransformer->transform($ps));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        $ps = $this->getPs($id);
        $ps->update(array_filter(request()->all()));
        return $this->successResponse(null, 'Mise à jour du Ps avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function destroy($id)
    {
        $ps = $this->getPs($id);
        $ps->delete();
        return $this->successResponse(null, 'Supression du Ps avec succès.');
    }

}
