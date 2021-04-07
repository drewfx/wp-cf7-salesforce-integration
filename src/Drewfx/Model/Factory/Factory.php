<?php

namespace Drewfx\Salesforce\Model\Factory;

use ReflectionClass;
use ReflectionException;

class Factory implements FactoryInterface
{
    /**
     * @param string $model
     * @return mixed|null
     */
    public function new(string $model)
    {
        try {
            $class = new ReflectionClass($model);

            if ($class->isInstantiable()) {
                return new $model();
            }
        } catch (ReflectionException $e) {
            // silence
        }

        return null;
    }
}
