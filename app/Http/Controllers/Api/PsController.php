<?php

namespace App\Http\Controllers\Api;

use App\Models\Ps;

use Exception;

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
        $ps = $this->validatePs();
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
        $ps = $this->getPsOrFail($id);
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
        $ps = $this->getPsOrFail($id);
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
        $ps = $this->getPsOrFail($id);
        $ps->delete();
        return $this->successResponse(null, 'Supression du Ps avec succès.');
    }

    private function validatePs(): array
    {
        $rules = [
            'nationalId' => 'required|unique:ps',
            'lastName' => 'nullable|string',
            'firstName' => 'nullable|string',
            'dateOfBirth' => 'nullable|string',
            'birthAddressCode' => 'nullable|string',
            'birthCountryCode' => 'nullable|string',
            'birthAddress' => 'nullable|string',
            'genderCode' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|string',
            'salutationCode' => 'nullable|string',
            'professions' => 'nullable|array',

            'professions.*.code' => 'nullable|string',
            'professions.*.categoryCode' => 'nullable|string',
            'professions.*.salutationCode' => 'nullable|string',
            'professions.*.lastName' => 'nullable|string',
            'professions.*.firstName' => 'nullable|string',
            'professions.*.expertises' => 'nullable|array',
            'professions.*.workSituations' => 'nullable|array',

            'professions.*.expertises.*.code' => 'nullable|string',
            'professions.*.expertises.*.categoryCode' => 'nullable|string',

            'professions.*.workSituations.*.modeCode' => 'nullable|string',
            'professions.*.workSituations.*.activitySectorCode' => 'nullable|string',
            'professions.*.workSituations.*.pharmacistTableSectionCode' => 'nullable|string',
            'professions.*.workSituations.*.roleCode' => 'nullable|string'
        ];

        $customMessages = [
            'required' => ':attribute est obligatoir.',
            'unique' => ':attribute existe déjà.'
        ];

        $ps = request()->validate($rules, $customMessages);

        foreach ($ps['professions'] as &$profession) {
            $profession['exProId'] = $profession['code'].$profession['categoryCode'];
        }

        return $ps;
    }

}
