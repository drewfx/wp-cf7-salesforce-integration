<?php

namespace Drewfx\Salesforce\Integration\Salesforce\Data;

use Drewfx\Salesforce\Integration\Salesforce\Configuration;

class Mapper
{
    protected $map;
    protected $data;
    protected $fields;

    public function __construct(Filter $filter)
    {
        $this->fields = [];
        $this->data = $filter->getData();

        $this->mappings();
        $this->execute();
        $this->addOwnerId();
    }

    protected function mappings() : void
    {
        $lines = explode(PHP_EOL, (string) Configuration::get('data_mappings'));

        foreach ($lines as $line) {
            [$field, $id] = explode('=', trim($line));

            if ($field && $id) {
                $this->map[] = [
                    'form_id' => $field,
                    'external_id' => $id
                ];
            }
        }
    }

    protected function execute() : array
    {
        foreach ($this->map as $mapping) {
            $this->add($mapping);
        }

        return $this->fields;
    }

    public function add($mapping) : void
    {
        $this->fields[$mapping['external_id']] = $this->data[$mapping['form_id']] ?? '';
    }

    protected function addOwnerId() : void
    {
        $this->fields['OwnerId'] = Configuration::get('default_lead_owner');
    }

    public function getFields() : array
    {
        return $this->fields;
    }

    public function getMap()
    {
        return $this->map;
    }
}
