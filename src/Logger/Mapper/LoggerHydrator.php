<?php

namespace Logger\Mapper;

use Zend\Stdlib\Hydrator\ClassMethods;
use Logger\Entity\Logger as LoggerEntity;

/**
 * Class LoggerHydrator
 *
 * @package Logger\Mapper
 */
class LoggerHydrator extends ClassMethods
{
    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     * @throws Exception\InvalidArgumentException
     */
    public function extract($object)
    {
        if (!$object instanceof LoggerEntity) {
            throw new Exception\InvalidArgumentException('$object must be an instance of Logger\Entity\Logger');
        }
        /* @var $object UserInterface*/
        $data = parent::extract($object);

        return $data;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @return UserInterface
     * @throws Exception\InvalidArgumentException
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof LoggerEntity) {
            throw new Exception\InvalidArgumentException('$object must be an instance of Logger\Entity\Logger');
        }
        return parent::hydrate($data, $object);
    }

}
