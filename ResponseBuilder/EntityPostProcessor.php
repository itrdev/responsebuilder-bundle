<?php

namespace Itr\ResponseBuilderBundle\ResponseBuilder;

interface EntityPostProcessor
{
    public function postProcess(array &$conversionArray);
}
