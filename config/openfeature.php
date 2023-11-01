<?php

return [

    /**
     * Specify the open feature provider you want to use.
     * 
     * Possible keys are: cloudbee, flagd, flipt
     */
    'provider' => [
        
        'cloudbee' => [
            'apiKey' => '....',
        ],

        'flagd' => [
            'protocol' => 'http',
            'host' => 'localhost',
            'port' => 8013,
            'secure' => true,
        ],

        'flipt' => [
            'host' => '',
            'token' => '',
            'namespace' => ''
        ]

    ],


    /**
     * Set the default client to use
     */
    'default' => 'main',

    /**
     * Specify your different clients you want to use in you project
     */
    'clients' => [

        'main' => [

            'context' => [
                'environment' => 'test',
            ],

            'mapper' => null,
        ]
    ]


];
