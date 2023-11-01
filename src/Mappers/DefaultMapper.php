<?php

namespace OpenFeature\Mappers;

use OpenFeature\implementation\flags\EvaluationContext;
use OpenFeature\implementation\flags\Attributes;


class DefaultMapper implements ContextMapper {

    public function map( mixed $scope, array $context ):EvaluationContext {

        // TODO: How to get id, dyanamically
        return new EvaluationContext( null, new Attributes( $context ) );
    }
}