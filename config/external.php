<?php

return [
    'platform' => [
        'api_id'             => env('EXTERNAL_PLATFORM_API_ID', '250keirin00005'),
        'api_key'            => env('EXTERNAL_PLATFORM_API_KEY', 'OjF9IPuk6thBwJsnTpFtdLUZ'),
        'authorization'      => env('EXTERNAL_PLATFORM_AUTHORIZATION', 'Basic a2VpcmluOnBhc3N3b3Jk'),
        'base_uri'           => env(
            'EXTERNAL_PLATFORM_BASE_URI',
            'https://nieqdb72z9.execute-api.ap-northeast-1.amazonaws.com/sitedev/api'
        ),
        'oldest_synced_date' => '20190415',
        'localized_lang' => 'jp',
    ],
];
