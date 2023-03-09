<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title and meta tags (SEO)
    |--------------------------------------------------------------------------
    |
    | You may use the SEO facade to set your page's title, description, and keywords.
    | @see https://splade.dev/docs/title-meta
    |
    */

    'defaults' => [
        'title' => env('APP_NAME', 'Laravel Splade'),
        'description' => 'Welkom op het videoportaal van Dansacademie Ri-Jo',
        'keywords' => ['videoportaal', 'instructies', 'dansen', 'stijldansen', 'Ri-Jo', 'Hellevoetsluis'],
    ],

    'title_prefix' => '',
    'title_separator' => '',
    'title_suffix' => '',

    'auto_canonical_link' => true,

    'open_graph' => [
        'auto_fill' => false,
        'image' => null,
        'site_name' => null,
        'title' => null,
        'type' => null, // 'WebPage'
        'url' => null,
    ],

    'twitter' => [
        'auto_fill' => false,
        'card' => null, // 'summary_large_image',
        'description' => null,
        'image' => null,
        'site' => null, // '@username',
        'title' => null,
    ],

];
