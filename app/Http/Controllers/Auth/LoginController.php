<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->route('welcome');
    }

    public function redirectToProvider($provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider): RedirectResponse
    {
        $providerUser = Socialite::driver($provider)->user();

        $jwt = $providerUser->token;
//        $refreshToken = $providerUser->refreshToken;
//        $expiresIn = $providerUser->expiresIn;

        list($header, $payload, $signature) = explode('.', $jwt);
        $payload_to_verify = utf8_decode($header . '.' . $payload);
        $decodedSignature = base64_decode(strtr($signature, '-_', '+/'));

        $publicKeyFile = config('auth.public_key_file');
        $publicKey = file_get_contents($publicKeyFile);

        $verified = openssl_verify($payload_to_verify, $decodedSignature, $publicKey, OPENSSL_ALGO_SHA256) == 1;
        $authTime = json_decode(base64_decode($payload), true)['auth_time'];
        $timeElapsed = date_timestamp_get(date_create()) - $authTime;

        session()->put([
            'token' => $jwt,
            'user_name' => $providerUser->getName(),
            'preferred_username' => Arr::get($providerUser->user, 'preferred_username'),
            'authenticated' => $verified,
            'time_elapsed' => $timeElapsed
        ]);

        // Redirect to welcome page
        return redirect()->route('welcome');
    }
}
