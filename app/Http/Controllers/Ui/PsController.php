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
        $response = $this->getResponse($psId);
        $body = json_decode($response->body(), true);

        if ($response->failed()) {
            return view('welcome', [
                'title' => 'Erreur',
                'message' => $this->getMessage($body, $response)
            ]);
        }

        return view('ps.show', ['ps' => $this->getProtectedPs($body)]);
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
                'message' => $this->getMessage($body, $response)
            ]);
        }

        Log::info("authenticated_user=".session('preferred_username')." modified_user=".$psId);

        return view('welcome', [
            'title' => 'Succès',
            'message' => 'Les modifications on bien été prises en compte'
        ]);
    }

    /**
     * @param $psId
     * @param $filteredArray
     * @return Response
     */
    private function putResponse($psId, $filteredArray): Response
    {
        if (config('app.env') == 'test-BAS') {
            return Http::put($this->inRassBaseUrl.urlencode($psId), [
                'mobile' => $filteredArray['phone'],
                'mail' => $filteredArray['email']
            ]);
        } else {
            return Http::put($this->psBaseUrl.urlencode($psId), $filteredArray);
        }
    }

    /**
     * @param $psId
     * @return Response
     */
    private function getResponse($psId): Response
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
    private function getMessage($body, $response): string
    {
        if (config('app.env') == 'test-BAS') {
            return $body ? $body[0]['errorMessage'] : 'response error '.$response->status();
        } else {
            return $body ? $body['message'] : 'response error '.$response->status();
        }
    }

    /**
     * @param $body
     * @return array
     */
    private function getProtectedPs($body): array
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
