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
        ],
        [
            'provider' => 'Startupful\WebpageManager\WebpageManagerServiceProvider',
            'tag' => 'webpage-manager-assets'
        ]
    ]
];