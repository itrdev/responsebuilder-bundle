<?php

namespace Itr\ResponseBuilderBundle\ResponseBuilder;

use Symfony\Component\HttpFoundation\Response;

class JsonResponseBuilder extends AbstractResponseBuilder
{

    protected function _prepareResponseObject($httpResponseCode, $data)
    {
        return new Response(json_encode($data), $httpResponseCode, array(
            'Content-Type' => 'application/json',
        ));
    }
}