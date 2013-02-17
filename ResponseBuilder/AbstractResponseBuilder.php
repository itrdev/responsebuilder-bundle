<?php

namespace Itr\ResponseBuilderBundle\ResponseBuilder;

use Symfony\Component\HttpFoundation\Response;

abstract class AbstractResponseBuilder
{
    /**
     * Converts ParameterBag or array into Response object
     *
     * @param array $parameters
     * @param int $httpStatusCode
     * @return mixed
     */
    public function build($parameters = array(), $httpStatusCode = 200)
    {
        $parameters = ($parameters instanceof ParameterBag) ? $parameters->toArray() : (array) $parameters;
        return $this->prepareResponseObject($parameters, $httpStatusCode);
    }

    /**
     * Returns new ParameterBag object
     *
     * @return ParameterBag
     */
    public function createParameterBag()
    {
        return new ParameterBag();
    }

    /**
     * This method should be implemented by the particular ResponseBuilder type object
     *
     * @abstract
     * @param array $data
     * @param int $httpResponseCode
     * @return mixed
     */
    abstract protected function prepareResponseObject(array $data, $httpResponseCode = 200);
}