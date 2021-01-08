<?php

namespace App\Http\Controllers\API\Facebook;

use App\Http\Controllers\Controller;
use App\Services\API\Facebook\FacebookLoginService;
use Laravel\Socialite\Facades\Socialite;

class FacebookLoginController extends Controller
{
    /**
     * @var FacebookLoginService
     */
    private $facebookLoginService;

    public function __construct(FacebookLoginService $facebookLoginService)
    {
        $this->facebookLoginService = $facebookLoginService;
    }

    /**
     * Redirects to Facebook login
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Facebook callback
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function callback()
    {
        $user = Socialite::driver('facebook')->stateless()->user();

        if ($this->facebookLoginService->loginOrCreate($user)) {
            return redirect()->route('home');
        }

        return redirect()->route('home')->withErrors(['message' => 'Can\'t login via Facebook!']);
    }
}
