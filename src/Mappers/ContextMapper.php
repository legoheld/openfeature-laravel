<?php

namespace OpenFeature\Mappers;

use OpenFeature\interfaces\flags\EvaluationContext;

interface ContextMapper
{

    public function map(array $context, mixed $scope): EvaluationContext;
}
