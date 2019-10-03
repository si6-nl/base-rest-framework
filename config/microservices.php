<?php

return [
    'host' => [
        'user'         => env('MICROSERVICES_USER_HOST', 'http://user.keirin250.test'),
        'auth'         => env('MICROSERVICES_AUTH_HOST', 'http://auth.keirin250.test'),
        'race'         => env('MICROSERVICES_RACE_HOST', 'http://race.keirin250.test'),
        'payment'      => env('MICROSERVICES_PAYMENT_HOST', 'http://payment.keirin250.test'),
        'betting'      => env('MICROSERVICES_BETTING_HOST', 'http://betting.keirin250.test'),
        'notification' => env('MICROSERVICES_NOTIFICATION_HOST', 'http://notification.keirin250.test'),
        'schedule'     => env('MICROSERVICES_SCHEDULE_HOST', 'http://schedule.keirin250.test'),
        'data'         => env('MICROSERVICES_DATA_HOST', 'http://data.keirin250.test'),
    ],
];
