<?php

namespace OpenFeature\Mappers;

use OpenFeature\interfaces\flags\EvaluationContext;

interface ContextMapper {

    public function map( mixed $scope, array $context ):EvaluationContext;
}