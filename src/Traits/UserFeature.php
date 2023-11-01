<?php

namespace OpenFeature\Traits;

use OpenFeature\Facades\OpenFeature;

trait UserFeature {

    public function features() {
        return OpenFeature::scope( $this );
    } 
}