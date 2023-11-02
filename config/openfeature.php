<?php

return [


    /**
     * Specify the open feature provider you want to use.
     *
     * Possible keys are: split, flagd, flipt
     */
    'provider' => [


        /* 
         * flagd example 
        'type' => 'flagd',
        'host' => 'localhost',
        'protocol' => 'http',
        'port' => 8013,
        */


        /* 
         * flipt example 
        'type' => 'flipt',
        'host' => 'localhost',
        'namespace' => 'ns',
        'apiToken' => 'token',
        */


        /* 
         * split example 
        'type' => 'split',
        'apiKey' => '....',
        'options' => [
            'cache' => [
            'adapter' => 'predis',
            'parameters' => [
                'scheme' => 'tcp',
                'host' => getenv('REDIS_HOST'),
                'port' => getenv('REDIS_PORT'),
                'timeout' => 881,
            ],
            'options' => [
                'prefix' => '',
            ],
        ],
        */



        /* 
         * cloudbees example 
        'type' => 'cloudbees',
        'apiKey' => '....',
        // ROXOptions
        'options' => [
           ...
        ],
        */
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
        ]
    ]


];
