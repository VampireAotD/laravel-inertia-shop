<?php

namespace App\Services\Admin\Users;

use App\DTO\RabbitMq\LogMessageDto;
use App\Http\Requests\Users\UserRequest;
use App\Models\User;
use \Symfony\Component\Serializer\Exception\ExceptionInterface;

class UserService
{
    /**
     * Update user roles
     *
     * @param User $user
     * @param UserRequest $request
     * @return bool
     */
    public function changeRole(User $user, UserRequest $request) : bool
    {
        try{
            if($user->syncRoles($request->input('role'))){
                $message = new LogMessageDto();
                $message->setChannel('users')
                    ->setMethod('notice')
                    ->setMessage('User granted role for user')
                    ->setAdditionalInformation([
                        'user' => $request->user()->name,
                        'granted to' => $user->name,
                        'role' => $request->input('role')
                    ])
                    ->convert('json');

                rabbitmq()->sendMessage($message, 'logs');

                return true;
            }
        }catch (ExceptionInterface $exception){
            $message = new LogMessageDto('users', 'warning', 'Exception while updating user roles',[
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);

            rabbitmq()->sendMessage($message, 'logs');
        }

        return false;
    }
}
