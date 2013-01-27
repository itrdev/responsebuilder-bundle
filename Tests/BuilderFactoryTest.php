<?php

namespace Itr\ResponseBuilderBundle\Tests;

use Itr\ResponseBuilderBundle\ResponseBuilder\ResponseBuilderFactory;

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
}
