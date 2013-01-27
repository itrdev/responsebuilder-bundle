<?php

namespace Itr\ResponseBuilderBundle\Tests;

use Itr\ResponseBuilderBundle\ResponseBuilder\ResponseBuilderFactory;
use Itr\ResponseBuilderBundle\ResponseBuilder\ParameterBag;

class BuilderFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testBuilderFactory()
    {
        $responseBuilder = new ResponseBuilderFactory('json');
        $this->assertEquals($responseBuilder->getDefault(), $responseBuilder->getBuilderForFormat('json'));

        $this->setExpectedException('Itr\ResponseBuilderBundle\Exception\InvalidBuilderFormatException');
        $responseBuilder->getBuilderForFormat('yml');
    }

    public function testWrongDefaultFormatException()
    {
        $this->setExpectedException('Itr\ResponseBuilderBundle\Exception\InvalidBuilderFormatException', 'Invalid builder format: xml');
        new ResponseBuilderFactory('xml');
    }

    public function testWrongFormatException()
    {
        $this->setExpectedException('Itr\ResponseBuilderBundle\Exception\InvalidBuilderFormatException', 'Invalid builder format: xml');
        $responseBuilder = new ResponseBuilderFactory('json');
        $responseBuilder->getBuilderForFormat('xml');
    }

    public function testJsonBuilder()
    {
        $responseBuilder = new ResponseBuilderFactory('json');
        $jsonBuilder = $responseBuilder->getDefault();

        $pb = new ParameterBag();
        $value = 'some value';
        $pb->set('first.second', $value);
        /** @var \Symfony\Component\HttpFoundation\Response $response  */
        $response = $jsonBuilder->build(200, $pb);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertJsonStringEqualsJsonString($response->getContent(), json_encode(array('first' => array('second' => $value))));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }
}
