<?php

namespace Drewfx\Salesforce\Model;

abstract class AbstractModel implements ModelInterface
{
    /** @var string */
    protected $primary = 'id';

    /** @var array */
    protected $attributes = [];

    /** @var array */
    protected $keys = [];

    public function getAttributes() : array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes) : self
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key) && ! is_int($key)) {
                $this->attributes[$key] = $value;
                $this->$key = $value;
            }
        }

        return $this;
    }

    public function getId() : int
    {
        return (int) $this->id;
    }

    public function getKeys() : array
    {
        return $this->keys;
    }

    public function getStringifiedKeys() : string
    {
        return implode(',', $this->keys);
    }
}
