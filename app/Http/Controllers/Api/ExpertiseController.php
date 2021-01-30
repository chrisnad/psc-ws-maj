<?php

namespace App\Http\Controllers\Api;

use App\Models\Ps;
use App\Psc\Transformers\ExpertiseTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExpertiseController extends ApiController
{

    protected $expertiseTransformer;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->expertiseTransformer = new ExpertiseTransformer();
    }

    /**
     * Display a listing of the resource.
     *
     * @param $nationalId
     * @param $codePro
     * @return mixed
     */
    public function index($nationalId, $codePro)
    {
        try {
            $professions = Ps::findOrFail($nationalId)->professions;
        } catch(ModelNotFoundException $e) {
            return $this->notFoundResponse("Ce professionel n'exist pas.");
        }

        $profession = $professions->firstWhere('code', $codePro);

        if ($profession) {
            $expertises = $profession->expertises;
            return $this->successResponse($this->expertiseTransformer->transformCollection($expertises->all()));
        } else {
            return $this->notFoundResponse("Cet exercice professionnel n'exist pas.");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $nationalId
     * @param $codePro
     * @return mixed
     */
    public function store($nationalId, $codePro)
    {
        $expertise = array_filter(request()->all());

        try {
            $professions = Ps::findOrFail($nationalId)->professions;
        } catch(ModelNotFoundException $e) {
            return $this->notFoundResponse("Ce professionel n'exist pas.");
        }

        $profession = $professions->firstWhere('code', $codePro);

        if ($profession) {
            $profession->expertises()->create($expertise);
            return $this->successResponse(null, "Creation de l'expertise avec succès.");
        } else {
            return $this->notFoundResponse("Cet exercice professionnel n'exist pas.");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $nationalId
     * @param $codePro
     * @param $code
     * @return mixed
     */
    public function show($nationalId, $codePro, $code)
    {
        try {
            $professions = Ps::findOrFail($nationalId)->professions;
        } catch(ModelNotFoundException $e) {
            return $this->notFoundResponse("Ce Ps n'exist pas.");
        }

        $profession = $professions->firstWhere('code', $codePro);

        if ($profession) {
            $expertises = $profession->expertises;
        } else {
            return $this->notFoundResponse("Cet exercice professionnel n'exist pas.");
        }

        $expertise = $expertises->firstWhere('code', $code);

        if ($expertise) {
            return $this->successResponse($this->expertiseTransformer->transform($expertise));
        } else {
            return $this->notFoundResponse("Cette expertise n'exist pas.");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $nationalId
     * @param $codePro
     * @param $code
     * @return mixed
     */
    public function update($nationalId, $codePro, $code)
    {
        try {
            $professions = Ps::findOrFail($nationalId)->professions;
        } catch(ModelNotFoundException $e) {
            return $this->notFoundResponse("Ce Ps n'exist pas.");
        }

        $profession = $professions->firstWhere('code', $codePro);

        if ($profession) {
            $expertises = $profession->expertises;
        } else {
            return $this->notFoundResponse("Cet exercice professionnel n'exist pas.");
        }

        $expertise = $expertises->firstWhere('code', $code);

        if ($expertise) {
            $expertise->update(array_filter(request()->all()), ['upsert' => true]);
            return $this->successResponse(null, "Mise à jour de l'expertise avec succès.");
        } else {
            return $this->notFoundResponse("Cette expertise n'existe pas.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $nationalId
     * @param $codePro
     * @param $code
     * @return mixed
     */
    public function destroy($nationalId, $codePro, $code)
    {
        try {
            $professions = Ps::findOrFail($nationalId)->professions;
        } catch(ModelNotFoundException $e) {
            return $this->notFoundResponse("Ce professionel n'exist pas.");
        }

        $profession = $professions->firstWhere('code', $codePro);

        if ($profession) {
            $expertises = $profession->expertises;
        } else {
            return $this->notFoundResponse("Cet exercice professionnel n'exist pas.");
        }

        $expertise = $expertises->firstWhere('code', $code);

        if ($expertise) {
            $expertise->delete();
            return $this->successResponse(null, "Suppression de l'expertise avec succès.");
        } else {
            return $this->notFoundResponse("Cette expertise n'existe pas.");
        }
    }
}
