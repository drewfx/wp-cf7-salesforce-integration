<?php

namespace Drewfx\Salesforce;

use Drewfx\Salesforce\Exception\NotFoundException;
use Drewfx\Salesforce\Exception\NotInstantiableException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Reflectionparameter;

class Container
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @param $name
     */
    public function remove(string $name) : void
    {
        if ($this->has($name)) {
            unset($this->items[$name]);
        }
    }

    /**
     * Cleaner wrapper for offsetExists()
     * @param $name
     * @return bool
     */
    public function has(string $name) : bool
    {
        return $this->exists($name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function exists(string $name) : bool
    {
        return isset($this->items[$name]);
    }

    /**
     * @param string $name
     * @param callable $closure
     */
    public function share(string $name, callable $closure) : void
    {
        $this->items[$name] = function () use ($closure) {
            static $resolved;

            if ( ! $resolved) {
                $resolved = $closure($this);
            }

            return $resolved;
        };
    }

    /**
     * @param $name
     * @return mixed
     * @throws NotFoundException
     * @throws NotInstantiableException|ReflectionException
     */
    public function autowire(string $name)
    {
        if ( ! class_exists($name)) {
            throw new NotFoundException;
        }

        $reflector = $this->getReflector($name);

        if ( ! $reflector->isInstantiable()) {
            throw new NotInstantiableException;
        }

        if ($constructor = $reflector->getConstructor()) {
            return $reflector->newInstanceArgs(
                $this->getReflectorConstructorDependencies($constructor)
            );
        }

        return new $name();
    }

    /**
     * @param string $name
     * @return ReflectionClass
     * @throws ReflectionException
     */
    protected function getReflector(string $name) : ReflectionClass
    {
        try {
            return new ReflectionClass($name);
        } catch (ReflectionException $e) {
            throw new $e;
        }
    }

    /**
     * @param ReflectionMethod $constructor
     * @return array
     * @throws NotFoundException
     * @throws NotInstantiableException
     * @throws ReflectionException
     */
    protected function getReflectorConstructorDependencies(ReflectionMethod $constructor) : array
    {
        return array_map(function (ReflectionParameter $dependency) {
            return $this->resolveReflectedDependency($dependency);
        }, $constructor->getParameters());
    }

    /**
     * @param Reflectionparameter $dependency
     * @return mixed
     * @throws NotFoundException
     * @throws NotInstantiableException
     * @throws ReflectionException
     */
    protected function resolveReflectedDependency(Reflectionparameter $dependency)
    {
        if (is_null($dependency->getClass())) {
            throw new NotFoundException;
        }

        return $this->get($dependency->getClass()->getName());
    }

    /**
     * @param string $name
     * @return mixed
     * @throws NotFoundException
     * @throws NotInstantiableException
     * @throws ReflectionException
     */
    public function get(string $name)
    {
        if ($this->has($name)) {
            return $this->items[$name]($this);
        }

        return $this->autowire($name);
    }

    /**
     * @param $name
     * @param $value
     */
    public function set(string $name, $value) : void
    {
        $this->items[$name] = $value;
    }

    /**
     * @param $property
     * @return mixed
     * @throws NotFoundException
     * @throws NotInstantiableException
     * @throws ReflectionException
     */
    public function __get($property)
    {
        return $this->get($property);
    }

    public function __set($property, $value)
    {
        $this->set($property, $value);
    }

    public function __isset($property) : bool
    {
        return isset($this->items[$property]);
    }
}
