<?php

namespace Drewfx\Salesforce\Model\Factory;

interface FactoryInterface
{
    /**
     * Creates a new instance of the model.
     * @param string $model
     * @return  mixed
     */
    public function new(string $model);
}
