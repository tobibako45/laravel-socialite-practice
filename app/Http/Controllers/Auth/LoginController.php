<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Hash;
use Auth;
use Socialite;
use App\User;
use Illuminate\Http\Request;

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
     * ログイン後の処理
     * AuthenticatesUsersから上書き
     */
    protected function authenticated()
    {
        return redirect('/')->with('my_status', __('ログインしました'));
    }

    /**
     * ログアウト後の処理
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect('/login')->with('my_status', __('ログアウトしました'));
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
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleProviderCallback(Request $request)
    {
        //// ほんとはこの辺をSocialiteで独自Driverを作ってやりたい ////

        $http = new \GuzzleHttp\Client;
        $access_token = $this->getAccessToken($http);
        $auth_user = $this->getAuthUser($http, $access_token);
        $user = User::where(['email' => $auth_user['email']])->first();

        if ($user) {
            Auth::login($user);
            return $this->authenticated($request, Auth::user());
        } else {
            $user = User::create([
                'name' => $auth_user['name'],
                'email' => $auth_user['email'],
                'password' => Hash::make($auth_user['name']),
            ]);
            Auth::login($user);
            return $this->authenticated($request, Auth::user());
        }
    }

    /**
     * Github用Callback
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleGithubProviderCallback(Request $request)
    {
        $socialUser = Socialite::driver('github')->stateless()->user();
        $user = User::where(['email' => $socialUser->getEmail()])->first();

        if ($user) {
            Auth::login($user);
            return $this->authenticated($request, Auth::user());
        } else {
            $user = User::create([
                'name' => $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'password' => Hash::make($socialUser->getNickname()), // NicknameをHash
            ]);
            Auth::login($user);
            return $this->authenticated($request, Auth::user());
        }
    }

    /**
     * @param $http
     * @return mixed
     */
    private function getAccessToken($http)
    {
        $response = $http->post('http://localhost:8080/oauth/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => env('PASSPORT_ID'),
                'client_secret' => env('PASSPORT_SECRET'),
                'redirect_uri' => env('PASSPORT_REDIRECT_URI'),
                'code' => request()->code
            ],
        ]);
        $responseBody = json_decode((string)$response->getBody(), true);
        return $responseBody['access_token'];
    }

    /**
     * @param $http
     * @param $access_token
     * @return mixed
     */
    private function getAuthUser($http, $access_token)
    {
        $response_user_data = $http->request('GET', 'http://localhost:8080/api/user', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token,
            ],
        ]);
        return json_decode((string)$response_user_data->getBody(), true);
    }
}
