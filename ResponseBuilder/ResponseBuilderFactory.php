<?php

namespace Itr\ResponseBuilderBundle\ResponseBuilder;

use Itr\ResponseBuilderBundle\Exception\InvalidBuilderFormatException;

class ResponseBuilderFactory
{
    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';

    const RESPONSE_BUILDER_CLASS_POSTFIX = 'ResponseBuilder';

    protected $allowedFormats = array(
      self::FORMAT_JSON,
    );

    private $_defaultFormat;

    /**
     * @param $defaultFormat
     * @throws \Itr\ResponseBuilderBundle\Exception\InvalidBuilderFormatException
     */
    public function __construct($defaultFormat)
    {
        if (!in_array($defaultFormat, $this->allowedFormats)) {
            throw new InvalidBuilderFormatException("Invalid builder format: " . $defaultFormat);
        }
        $this->_defaultFormat = $defaultFormat;
    }

    /**
     * Returns default builder
     * @return mixed
     */
    public function getDefault()
    {
        return $this->fetchBuilder($this->_defaultFormat);
    }

    /**
     * Returns builder by format
     * @param $format
     * @return mixed
     * @throws \Itr\ResponseBuilderBundle\Exception\InvalidBuilderFormatException
     */
    public function getBuilderForFormat($format)
    {
        if (!in_array($format, $this->allowedFormats)) {
            throw new InvalidBuilderFormatException("Invalid builder format: " . $format);
        }
        return $this->fetchBuilder($format);
    }

    /**
     * Creates builder by class
     * @param $format
     * @return mixed
     */
    protected function fetchBuilder($format)
    {
        $className = __NAMESPACE__ . '\\' . ucfirst(strtolower($format)) . self::RESPONSE_BUILDER_CLASS_POSTFIX;
        return new $className();
    }
}