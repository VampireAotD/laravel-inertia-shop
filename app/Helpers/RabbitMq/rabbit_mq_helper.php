<?php
if(!function_exists('rabbitmq')){
    function rabbitmq(string $host = null, string $port = null, string $user = null, string $password = null){
        if(is_null($host)) $host = env('RABBIT_MQ_HOST');
        if(is_null($port)) $port = env('RABBIT_MQ_PORT');
        if(is_null($user)) $user = env('RABBIT_MQ_USER');
        if(is_null($password)) $password = env('RABBIT_MQ_PASSWORD');

        return new App\Helpers\RabbitMq\RabbitMq($host, $port, $user, $password);
    }
}