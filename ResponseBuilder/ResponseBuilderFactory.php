<?php

namespace Itr\ResponseBuilderBundle\ResponseBuilder;

use Itr\ResponseBuilderBundle\Exception\InvalidBuilderFormatException;

class ResponseBuilderFactory
{
    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';

    const BUILDER_NAMESPACE = 'Itr\ResponseBuilderBundle\ResponseBuilder';
    const RESPONSE_BUILDER_CLASS_POSTFIX = 'ResponseBuilder';

    protected $_allowedFormats = array(
      self::FORMAT_JSON,
//      self::FORMAT_XML,
    );

    private $_defaultFormat;

    public function __construct($defaultFormat)
    {
        if (!in_array($defaultFormat, $this->_allowedFormats))
        {
            throw new InvalidBuilderFormatException("Invalid builder format: " . $defaultFormat);
        }
        $this->_defaultFormat = $defaultFormat;
    }

    public function getDefault()
    {
        return $this->_fetchBuilder($this->_defaultFormat);
    }

    public function getBuilderForFormat($format)
    {
        if (!in_array($format, $this->_allowedFormats))
        {
            throw new InvalidBuilderFormatException("Invalid builder format: " . $format);
        }
        return $this->_fetchBuilder($format);
    }

    protected function _fetchBuilder($format)
    {
        $className = self::BUILDER_NAMESPACE . '\\' . ucfirst(strtolower($format)) . self::RESPONSE_BUILDER_CLASS_POSTFIX;
        return new $className();
    }
}