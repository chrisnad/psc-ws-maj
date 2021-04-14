<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PsController extends Controller
{

    protected $psBaseUrl;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->psBaseUrl = config("app.api_url").'/ps/';
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

        // TODO: GET THE ps THE RIGHT WAY
        $response = Http::get($this->psBaseUrl.urlencode($psId));
        $body = json_decode($response->body(), true);

        if ($response->failed()) {
            return view('welcome', [
                'title' => 'Erreur',
                'message' => $body["message"]
            ]);
        }

        $protectedPs = [
            "nationalId" => $body["data"]["nationalId"],
            "lastName" => $body["data"]["lastName"],
            "firstName" => $body["data"]["firstName"],
            "phone" => $body["data"]["phone"],
            "email" => $body["data"]["email"]];

        return view('ps.show', ['ps' => $protectedPs]);
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

        // TODO: this updates in our database, adapt for WS
        $response = Http::put($this->psBaseUrl.urlencode($psId), array_filter(request()->all()));
        $body = json_decode($response->body(), true);

        if ($response->failed()) {
            return view('welcome', [
                'title' => 'Erreur',
                'message' => $body["message"]
            ]);
        }

        Log::info("authenticated_user=".session('preferred_username')." modified_user=".$psId);

        return view('welcome', [
            'title' => 'Succès',
            'message' => 'Les modifications on bien été prises en compte'
        ]);
    }

}
