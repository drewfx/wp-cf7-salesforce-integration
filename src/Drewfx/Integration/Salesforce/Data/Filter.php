<?php

namespace Drewfx\Salesforce\Integration\Salesforce\Data;

use ArrayAccess;

class Filter implements ArrayAccess
{
    protected $data;

    public function __construct(array $post)
    {
        $this->data = $post;
        $this->execute();
    }

    protected function execute() : void
    {
        /* Phone Format */
        if ($this->offsetExists('phone-number')) {
            $number = preg_replace(
                '/\((\d{3})\) (\d{3})-(\d{4})/',
                '$1-$2-$3',
                $this->offsetGet('phone-number')
            );

            $this->offsetSet(
                'phone-number',
                $number
            );
        }

        /* Parse the terrible checkboxes from CF7 @todo: refactor/dynamic */
        if ($this->offsetExists('interested')) {
            $interests = $this->offsetGet('interested');

            foreach ($interests as $key => $interest) {
                switch ($interest) {
                    case 'Interior':
                        $this->offsetSet('interior', true);
                        break;
                    case 'Exterior':
                        $this->offsetSet('exterior', true);
                        break;
                    case 'Holiday':
                        $this->offsetSet('holiday', true);
                        break;
                    case 'Irrigation':
                        $this->offsetSet('irrigation', true);
                        break;
                    case 'Living/Moss Wall':
                        $this->offsetSet('living_wall', true);
                        $this->offsetSet('moss_wall', true);
                        break;
                    case 'Maintenance':
                        if ($this->offsetExists('interior')) {
                            $this->offsetSet('interior_maintenance', true);
                        }
                        if ($this->offsetExists('exterior')) {
                            $this->offsetSet('exterior_maintenance', true);
                        }
                        break;
                }
            }
        }

        /*
        * State was changed to dropdown which passes the value as an array, we need to remove the array value
        * as Salesforce wants a string value.  @todo: add type enforcement for fields?
         */
        if ($this->offsetExists('state')) {
            $value = $this->offsetGet('state');

            if (is_array($value)) {
                $this->offsetSet('state', reset($value));
            }
        }
    }

    public function offsetExists($offset) : bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet($offset, $value) : void
    {
        $this->data[$offset] = $value;
    }

    public function offsetEmpty($offset) : bool
    {
        return empty($this->data[$offset]);
    }

    public function offsetUnset($offset) : void
    {
        unset($this->data[$offset]);
    }

    public function getData() : array
    {
        return (array) $this->data;
    }
}
