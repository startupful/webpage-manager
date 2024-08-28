<?php

return [
    'external_assets' => [
        [
            'provider' => 'VanOns\Laraberg\LarabergServiceProvider',
            'tag' => 'public'
        ],
        [
            'provider' => 'Startupful\WebpageManager\WebpageManagerServiceProvider',
            'tag' => 'laraberg-assets'
        ]
    ]
];