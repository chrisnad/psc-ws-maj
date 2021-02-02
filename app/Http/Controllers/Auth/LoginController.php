<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
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

    public function redirectToProvider($provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $providerUser = Socialite::driver($provider)->user();
        //$providerUser = Socialite::driver($provider)->stateless()->user();

        // Get user from DB or create if provider id is new
        $user = User::firstOrCreate(
            [
                'provider'    => $provider,
                'provider_id' => $providerUser->getId(),
            ],
            [
                'name'  => $providerUser->getName(),
                'last_name' => Arr::get($providerUser->user, 'family_name'),
                'preferred_username' => Arr::get($providerUser->user, 'preferred_username'),
            ]
        );

        // Login the user
        Auth::login($user, true);
        //JWTAuth::fromUser($user)

        // Redirect to welcome page
        return redirect()->route('welcome');
    }
}
