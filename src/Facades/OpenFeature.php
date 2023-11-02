<?php

namespace OpenFeature\Facades;

use Illuminate\Support\Facades\Facade;


class OpenFeature extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'OpenFeature';
    }
}
