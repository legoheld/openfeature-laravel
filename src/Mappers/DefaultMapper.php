<?php

namespace OpenFeature\Laravel\Mappers;

use OpenFeature\implementation\flags\EvaluationContext;
use OpenFeature\implementation\flags\Attributes;


class DefaultMapper implements ContextMapper
{

    public function map(array $context, mixed $scope = null): EvaluationContext
    {

        // try to extract id from given scope
        $id = optional($scope)->id;

        // TODO: How to get id, dyanamically
        return new EvaluationContext($id, new Attributes($context));
    }
}
