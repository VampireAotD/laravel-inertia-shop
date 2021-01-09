<?php
if (!function_exists('rabbitmq')) {
    function rabbitmq(string $host = null, string $port = null, string $user = null, string $password = null)
    {
        if (is_null($host)) $host = config('rabbitmq.host');
        if (is_null($port)) $port = config('rabbitmq.port');
        if (is_null($user)) $user = config('rabbitmq.user');
        if (is_null($password)) $password = config('rabbitmq.password');

        return new App\Helpers\RabbitMq\RabbitMq($host, $port, $user, $password);
    }
}
