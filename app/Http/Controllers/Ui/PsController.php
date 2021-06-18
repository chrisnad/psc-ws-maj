<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class PsController
 * @package App\Http\Controllers\Ui
 */
class PsController extends Controller
{

    /**
     * @var string
     */
    protected $psBaseUrl;

    /**
     * @var string
     */
    protected $inRassBaseUrl;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->psBaseUrl = config("app.api_url").'/ps/';
        $this->inRassBaseUrl = config("app.in_rass_url");
        $this->middleware('auth');
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

        $psId = $request->input('id');

        // GET REQUEST
        $response = $this->getPsById($psId);
        $body = json_decode($response->body(), true);

        if ($response->failed()) {
            return view('welcome', [
                'title' => 'Erreur',
                'message' => $this->getErrorMessage($body, $response)
            ]);
        }

        return view('ps.show', ['ps' => $this->getFilteredPs($body)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $psId
     * @return array|Application|Factory|View
     */
    public function update($psId)
    {
        if (! request()->input('phone') and ! request()->input('email')){
            return view('welcome', [
                'title' => 'Info',
                'message' => "Aucune mise à jour n'a été éffectuée"
            ]);
        }

        request()->validate([
            'conditions' => 'accepted'
        ]);

        $response = $this->putResponse($psId, array_filter(request()->all()));
        $body = json_decode($response->body(), true);

        if ($response->failed()) {
            return view('welcome', [
                'title' => 'Erreur',
                'message' => $this->getErrorMessage($body, $response)
            ]);
        }

        Log::info("authenticated_user=".session('preferred_username')." modified_user=".$psId);

        return view('welcome', [
            'title' => 'Succès',
            'message' => 'Les modifications ont bien été prises en compte'
        ]);
    }

    /**
     * @param $psId
     * @param $filteredArray
     * @return Response
     */
    private function putResponse($psId, $filteredArray): Response
    {
        // Put in local DB via our API
        $response = Http::put($this->psBaseUrl.urlencode($psId), $filteredArray);
        // Push to IN
        // TODO: push to job queue and do not return a response
        if ($response->successful()) {
            $response = Http::put($this->inRassBaseUrl.'?nationalId='.$psId, [
                'phone' => $filteredArray['phone'],
                'email' => $filteredArray['email']
            ]);
        }
        return $response;
    }

    /**
     * @param $psId
     * @return Response
     */
    private function getPsById($psId): Response
    {
        if (config('app.env') == 'test-BAS') {
            return Http::get($this->inRassBaseUrl, [
                'nationalId' => $psId
            ]);
        } else {
            return Http::get($this->psBaseUrl.urlencode($psId));
        }
    }

    /**
     * @param $body
     * @param $response
     * @return string
     */
    private function getErrorMessage($body, $response): string
    {
        if (config('app.env') == 'test-BAS') {
            return isset($body[0]['errorMessage']) ? $body[0]['errorMessage'] : 'response error '.$response->status();
        } else {
            return isset($body['message']) ? $body['message'] : 'response error '.$response->status();
        }
    }

    /**
     * @param $body
     * @return array
     */
    private function getFilteredPs($body): array
    {
        if (config('app.env') == 'test-BAS') {
            return [
                "nationalId" => $body["nationalId"],
                "lastName" => $body["lastName"],
                "firstName" => $body["firstName"],
                "phone" => $body["userCivilStatusInfo"]["phone"],
                "email" => $body["userCivilStatusInfo"]["email"]
            ];
        } else {
            return [
                "nationalId" => $body["data"]["nationalId"],
                "lastName" => $body["data"]["lastName"],
                "firstName" => $body["data"]["firstName"],
                "phone" => $body["data"]["phone"],
                "email" => $body["data"]["email"]
            ];
        }
    }

}
