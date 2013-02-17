<?php

namespace Itr\ResponseBuilderBundle\ResponseBuilder;

use Itr\ResponseBuilderBundle\Exception\InvalidParameterException;
use Itr\ResponseBuilderBundle\Exception\ExecuteAsDeferredEntityException;
use Itr\ResponseBuilderBundle\Exception\ExecuteAsDeferredCollectionException;

class ParameterBag
{
    const KEY_PATH_SEPARATOR = '.';
    const KEY_ARRAY_ELEMENT = '__array__';
    const GETTER_PREFIX = 'get';

    private $parameters;
    private $processed = array();

    public function __construct(array $initialParameters = array())
    {
        $this->parameters = $initialParameters;
    }

    public function set($key, $value)
    {
        $data = &$this->_findInjectionPoint($key, true);
        $data = $value;
    }

    public function setEntity($key, $entity, $postProcessor = null)
    {
        if (!is_object($entity) || $entity instanceof \stdClass)
        {
            throw new InvalidParameterException(get_class($entity) . " is not an entity");
        }

        // Finding the injection point and preparing our conversion array that will be injected.
        // Also, init the deferred execution queue for collections (these need to be injected after the conversion array
        // is ready, otherwise some values may be overwritten which is not we want).
        $element = &$this->_findInjectionPoint($key, true);
        $deferredExecutionQueue = new \SplQueue();
        $conversionArray = array();

        // Getting entity properties, will process all properties, however, public & protected need to have a getter
        // available in order to be processed.

        $refClass = new \ReflectionClass($entity);
        if ($entity instanceof \Doctrine\ORM\Proxy\Proxy) {
            $properties = $refClass->getParentClass()->getProperties();
        } else {
            $properties = $refClass->getProperties();
        }

        foreach ($properties as $property)
        {

            $value = null;
            // for protected and private methods we should call getter
            if (($property->isPrivate() || $property->isProtected()) && $refClass->hasMethod(self::GETTER_PREFIX . ucfirst($property->getName())))
            {
                $value = $refClass->getMethod(self::GETTER_PREFIX . ucfirst($property->getName()))->invoke($entity);
            }
            elseif (!$property->isPrivate() && !$property->isProtected())
            {
                // otherwise just get the value directly
                $value = $property->getValue($entity);
            }

            try {
                $conversionArray[$property->getName()] = $this->processPropertyValue($property, $value);

            } catch (ExecuteAsDeferredEntityException $e) {
                $deferredExecutionQueue->enqueue(array('name' => $property->getName(), 'entity' => $value));

            } catch (ExecuteAsDeferredCollectionException $e) {
                $deferredExecutionQueue->enqueue(array('name' => $property->getName(), 'collection' => $value));
            }
        }

        // Updating parameters
        $element = $conversionArray;
        // Executing deferred entity & collection insertions
        $this->_executeDeferredInsertions($key, $deferredExecutionQueue);
        // If post-processor is available, we execute it supplying the element, so it can trim the fields that aren't
        // necessary. If post-processor variable is an array, then we execute the whole chain.
        if ($postProcessor)
        {
            $this->_executePostProcessor($postProcessor, $element);
        }
    }

    public function setEntityCollection($key, $collection, $postProcessor = null)
    {
        $injectionPath = $key . self::KEY_PATH_SEPARATOR . self::KEY_ARRAY_ELEMENT;
        foreach ($collection as $item)
        {
            if (is_object($item))
            {
                $this->setEntity($injectionPath, $item);
            }
            else
            {
                $element = &$this->_findInjectionPoint($injectionPath, true);
                $element = $item;
            }
        }
        // If post-processor is available, we execute it supplying the element, so it can trim the fields that aren't
        // necessary. If post-processor variable is an array, then we execute the whole chain.
        if ($postProcessor)
        {
            $element = &$this->_findInjectionPoint($key);
            $this->_executePostProcessor($postProcessor, $element);
        }
    }

    // TODO: remove method should remove key and value, not just set value as null
    public function remove($key)
    {
        $element = &$this->_findInjectionPoint($key);
        if ($element)
        {
            $element = null;
        }
    }

    public function get($key)
    {
        return $this->_findInjectionPoint($key);
    }

    public function has($key)
    {
        return !is_null($this->_findInjectionPoint($key));
    }

    public function toArray()
    {
        return $this->parameters;
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    public function __isset($key)
    {
        return $this->has($key);
    }

    public function __unset($key)
    {
        $this->remove($key);
    }

    public function debug()
    {
        var_dump($this->parameters);
        die;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    protected function processPropertyValue($property, $value = null)
    {
        if ($value instanceof \DateTime) {
            return $value->getTimestamp();
        }

        if (is_object($value) && !$value instanceof \stdClass && !$value instanceof \ArrayAccess) {
            throw new ExecuteAsDeferredEntityException();
        }

        if (is_array($value) || $value instanceof \ArrayAccess) {
            throw new ExecuteAsDeferredCollectionException();
        }

        return $value;
    }

    protected function &_findInjectionPoint($key, $allowCreation = false)
    {
        $path = explode(self::KEY_PATH_SEPARATOR, $key);
        $element = &$this->parameters;
        while ($currentKey = array_shift($path))
        {
            if (!$allowCreation && is_null($element))
            {
                $element = null;
                return $element;
            }
            if (self::KEY_ARRAY_ELEMENT == $currentKey)
            {
                $element = (is_array($element)) ? $element : array();
                if (count($path) > 0) {
                    $currentKey = array_shift($path);
                    end($element);
                    return $element[key($element)][$currentKey];
                } else {
                    array_push($element, null);
                    end($element);
                    return $element[key($element)];
                }
            }
            if (is_null($element))
            {
                $element = array($currentKey => null);
            }

            $element = &$element[$currentKey];
        }
        return $element;
    }

    protected function _executeDeferredInsertions($key, \SplQueue $deferredExecutionQueue)
    {
        foreach ($deferredExecutionQueue as $deferredItem)
        {
            $injectionPath = $key . self::KEY_PATH_SEPARATOR . $deferredItem['name'];
            if (isset($deferredItem['entity']))
            {
                $this->setEntity($injectionPath, $deferredItem['entity']);
            }
            elseif (isset($deferredItem['collection']))
            {
                $this->setEntityCollection($injectionPath, $deferredItem['collection']);
            }
        }
    }

    protected function _executePostProcessor($postProcessor, &$element)
    {
        $postProcessor = (is_array($postProcessor)) ? $postProcessor : array($postProcessor);
        foreach ($postProcessor as $processor)
        {
            if (!$processor instanceof EntityPostProcessor)
            {
                throw new InvalidParameterException(get_class($processor) . " does not implement the EntityPostProcessor interface");
            }
            $processor->postProcess($element);
        }
    }
}