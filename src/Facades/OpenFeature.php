<?php

namespace OpenFeature\Facades;

use Illuminate\Support\Facades\Facade;
use OpenFeature\OpenFeature as OpenFeatureImpl;


class OpenFeature extends Facade {

	protected static function getFacadeAccessor() {
        return OpenFeatureImpl::class;
    }
}
