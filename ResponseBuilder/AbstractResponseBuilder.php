<?php

namespace Itr\ResponseBuilderBundle\ResponseBuilder;

use Symfony\Component\HttpFoundation\Response;

abstract class AbstractResponseBuilder
{

    public function build($httpResponseCode, $parameters = array())
    {
        $parameters = ($parameters instanceof ParameterBag) ? $parameters->toArray() : $parameters;
        return $this->_prepareResponseObject($httpResponseCode, $parameters);
    }

    public function createParameterBag()
    {
        return new ParameterBag();
    }

    abstract protected function _prepareResponseObject($httpResponseCode, array $data);
}