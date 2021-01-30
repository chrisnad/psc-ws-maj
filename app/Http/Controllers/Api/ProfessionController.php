<?php

namespace App\Http\Controllers\Api;

use App\Models\Ps;
use App\Psc\Transformers\ExpertiseTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProfessionController extends ApiController
{

    protected $professionTransformer;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->professionTransformer = new ExpertiseTransformer();
    }

    /**
     * Display a listing of the resource.
     *
     * @param $nationalId
     * @return mixed
     */
    public function index($nationalId)
    {
        try {
            $professions = Ps::findOrFail($nationalId)->professions;
        } catch(ModelNotFoundException $e) {
            return $this->notFoundResponse("Ce professionel n'exist pas.");
        }

        return $this->successResponse($this->professionTransformer->transformCollection($professions->all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $nationalId
     * @return mixed
     */
    public function store($nationalId)
    {
        $profession = array_filter(request()->all());

        try {
            $ps = Ps::findOrFail($nationalId);
        } catch(ModelNotFoundException $e) {
            return $this->notFoundResponse("Ce professionel n'exist pas.");
        }

        $ps->professions()->create($profession);

        return $this->successResponse(null, "Creation de l'exercice professionnel avec succès.");
    }

    /**
     * Display the specified resource.
     *
     * @param $nationalId
     * @param $code
     * @return mixed
     */
    public function show($nationalId, $code)
    {
        try {
            $professions = Ps::findOrFail($nationalId)->professions;
        } catch(ModelNotFoundException $e) {
            return $this->notFoundResponse("Ce Ps n'exist pas.");
        }

        $profession = $professions->firstWhere('code', $code);

        if ($profession) {
            return $this->successResponse($this->professionTransformer->transform($profession));
        } else {
            return $this->notFoundResponse("Cet exercice professionnel n'exist pas.");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $nationalId
     * @param $code
     * @return mixed
     */
    public function update($nationalId, $code)
    {
        try {
            $professions = Ps::findOrFail($nationalId)->professions;
        } catch(ModelNotFoundException $e) {
            return $this->notFoundResponse("Ce Ps n'exist pas.");
        }

        $profession = $professions->firstWhere('code', $code);

        if ($profession) {
            $profession->update(array_filter(request()->all()), ['upsert' => true]);
            return $this->successResponse(null, "Mise à jour de l'exercise pro avec succès.");
        } else {
            return $this->notFoundResponse("Cet exercice professionnel n'exist pas.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $nationalId
     * @param $code
     * @return mixed
     */
    public function destroy($nationalId, $code)
    {
        try {
            $professions = Ps::findOrFail($nationalId)->professions;
        } catch(ModelNotFoundException $e) {
            return $this->notFoundResponse("Ce professionel n'exist pas.");
        }

        $profession = $professions->firstWhere('code', $code);

        if ($profession) {
            $profession->delete();
            return $this->successResponse(null, "Suppression de l'exercise pro avec succès.");
        } else {
            return $this->notFoundResponse("Cet exercice professionnel n'exist pas.");
        }
    }

}
