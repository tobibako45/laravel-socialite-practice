<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Hash;
use Auth;
use Socialite;
use App\User;

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
     * OAuth認証先にリダイレクト
     * @param string $provider
     * @return
     */
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * OAuth認証の結果受け取り
     * @param $provider
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleProviderCallback($provider)
    {
        //// ほんとはこの辺をSocialiteで独自Driverを作ってやりたい ////
        // $socialUser = Socialite::driver($provider)->user();
        // $socialUser = Socialite::driver($provider)->stateless()->user();
        // $socialUser = Socialite::with($provider)->stateless()->user();

        $http = new \GuzzleHttp\Client;
        $response = $http->post('http://localhost:8080/oauth/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => env('PASSPORT_ID'),
                'client_secret' => env('PASSPORT_SECRET'),
                'redirect_uri' => env('APP_URL') . '/login/passport/callback',
                'code' => request()->code
            ],
        ]);

        $responseBody = json_decode((string)$response->getBody(), true);

        $response_user = $http->request('GET', 'http://localhost:8080/api/user', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $responseBody['access_token'],
            ],
        ]);

        $user_data = json_decode((string)$response_user->getBody(), true);

        $user = User::where(['email' => $user_data['email']])->first();

        if ($user) {
            Auth::login($user);
            return redirect('/home');
        } else {
            $user = User::create([
                'name' => $user_data['name'],
                'email' => $user_data['email'],
                'password' => Hash::make($user_data['name']),
            ]);
            Auth::login($user);
            return redirect('/home');
        }
    }
}
