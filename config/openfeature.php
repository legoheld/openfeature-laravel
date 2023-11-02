<?php

return [


    /**
     * Specify the open feature provider you want to use.
     *
     * Possible keys are: cloudbee, flagd, flipt
     */
    'provider' => [

        'type' => 'cloudbee',
        'apiKey' => '....',

    ],


    /**
     * Set the default client to use
     */
    'defaultClient' => 'main',

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
