<?php

namespace App\Services\API\Facebook;

use App\DTO\RabbitMq\LogMessageDto;
use App\Models\User;
use Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Two\User as OAuthUser;

class FacebookLoginService
{
    /**
     * Create new user or login him if he already exists
     *
     * @param OAuthUser $facebookUser
     * @return bool
     * @throws \Throwable
     */
    public function loginOrCreate(OAuthUser $facebookUser)
    {
        if ($user = User::where('email', $facebookUser->getEmail())->first()) {
            Auth::login($user);
            return true;
        }

        try {
            \DB::beginTransaction();

            if (\App\Models\OAuthUser::create(
                [
                    'email' => $facebookUser->getEmail(),
                    'provider' => 'facebook',
                    'provider_id' => $facebookUser->getId()
                ]
            )) {
                if ($user = User::create(
                    [
                        'name' => $facebookUser->getName(),
                        'email' => $facebookUser->getEmail(),
                        'password' => \Hash::make(Str::random()),
                        'profile_photo_path' => $facebookUser->getAvatar()
                    ]
                )) {
                    \DB::commit();
                    Auth::login($user);
                }
            }
            \DB::rollBack();
        } catch (\Exception $exception) {
            rabbitmq()->sendMessage(new LogMessageDto('users', 'warning', 'Error while facebook auth', [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
            ]), 'logs');
            return false;
        }

        return true;
    }
}
