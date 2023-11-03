<?php

namespace OpenFeature\Laravel\Traits;

use OpenFeature\Laravel\Facades\OpenFeature;

trait HasFeatures {

    public function features() {
        return OpenFeature::for( $this );
    } 
}