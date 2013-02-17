<?php

namespace Itr\ResponseBuilderBundle\ResponseBuilder;

use Symfony\Component\HttpFoundation\Response;

class JsonResponseBuilder extends AbstractResponseBuilder
{

    /**
     * Builds response as a JSON object
     *
     * @param array $data
     * @param int $httpStatusCode
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function prepareResponseObject(array $data, $httpStatusCode = 200)
    {
        return new Response(json_encode($data), $httpStatusCode, array(
            'Content-Type' => 'application/json',
        ));
    }
}