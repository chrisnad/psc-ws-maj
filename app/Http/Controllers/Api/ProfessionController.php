<?php

namespace App\Http\Controllers\Api;

use App\Models\Profession;
use App\Models\Ps;
use App\Psc\Transformers\ProfessionTransformer;
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
        $this->professionTransformer = new ProfessionTransformer();
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
            return $this->responseNotFound("Ce professionel n'exist pas.");
        }

        return $this->respond([
            'data' => $this->professionTransformer->transformCollection($professions->all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $nationalId
     * @return mixed
     */
    public function store($nationalId)
    {
        $profession = request()->all();

        try {
            $ps = Ps::findOrFail($nationalId);
        } catch(ModelNotFoundException $e) {
            return $this->responseNotFound("Ce professionel n'exist pas.");
        }

        $ps->professions()->create($profession);

        return $this->respond([
            'message' => "Creation de l'exercice professionnel avec succès."
        ]);
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
            return $this->responseNotFound("Ce Ps n'exist pas.");
        }

        $profession = $professions->firstWhere('code', $code);

        if ($profession) {
            return $this->respond([
                'data' => $this->professionTransformer->transform($profession)
            ]);
        } else {
            return $this->responseNotFound("Cet exercice professionnel n'exist pas.");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $code
     * @return mixed
     */
    public function update($nationalId, $code)
    {

        try {
            $professions = Ps::findOrFail($nationalId)->professions;
        } catch(ModelNotFoundException $e) {
            return $this->responseNotFound("Ce Ps n'exist pas.");
        }

        $profession = $professions->firstWhere('code', $code);

        if ($profession) {
            $profession->update(request()->all(), ['upsert' => true]);
            return $this->respond([
                'message' => "Mise à jour de l'exercise pro avec succès."
            ]);
        } else {
            return $this->responseNotFound("Cet exercice professionnel n'exist pas.");
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
        //
    }

}
