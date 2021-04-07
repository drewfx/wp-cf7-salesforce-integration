<?php

namespace Drewfx\Salesforce\Model\Repository;

use Drewfx\Salesforce\Database\Database;
use Drewfx\Salesforce\Model\Factory\Factory;
use Drewfx\Salesforce\Model\ModelInterface;

abstract class AbstractRepository
{
    /** @var Database */
    protected $database;

    /** @var Factory */
    protected $factory;

    /** @var string */
    protected $model;

    /** @var */
    protected $table;

    /** @var string */
    protected $primaryKey = 'id';

    /**
     * @param Database $database
     * @param Factory $factory
     */
    public function __construct(Database $database, Factory $factory)
    {
        $this->database = $database;
        $this->factory = $factory;
    }

    /**
     * @return ModelInterface|null
     */
    public function new() : ?ModelInterface
    {
        return $this->factory->new($this->model);
    }

    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function delete($column, $value)
    {
        return $this->database->raw(
            sprintf('delete from %s where %s = %s;', $this->table, $column, $value)
        );
    }

    public function all() : array
    {
        $return = [];
        $rows = $this->database->query(
            sprintf('select * from %s', $this->table)
        )->all();

        foreach ($rows as $row) {
            if ( ! empty($row)) {
                $return[] = $this->hydrate($row);
            }
        }

        return $return;
    }

    /**
     * @param array $attributes
     * @return ModelInterface|null
     */
    protected function hydrate(array $attributes) : ?ModelInterface
    {
        if ($attributes && is_array($attributes)) {
            /** @var ModelInterface $item */
            $item = $this->factory->new($this->model);

            return $item->setAttributes($attributes);
        }

        return null;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id) : ?ModelInterface
    {
        $sql = sprintf(
            'select * from %s where %s = :id;',
            $this->table,
            $this->primaryKey
        );

        $row = $this->database->query($sql, [':id' => $id])->first();

        return ! empty($row) ? $this->hydrate($row) : null;
    }

    /**
     * Finds a SQL query by bound parameters such as [':email', $email]
     * @param string $sql
     * @param array $parameters
     * @return ModelInterface|null
     */
    public function findBy(string $sql, array $parameters) : ?ModelInterface
    {
        $row = $this->database->query($sql, $parameters)->first();

        return ! empty($row) ? $this->hydrate($row) : null;
    }

    /**
     * @param string $sql
     * @param array $parameters
     * @param bool $raw
     * @return array
     */
    public function findAllBy(string $sql, array $parameters, bool $raw = false) : array
    {
        $rows = $this->database->query($sql, $parameters)->all();

        if ($raw) {
            return $rows;
        }

        $return = [];

        foreach ($rows as $row) {
            $return[] = ! empty($row) ? $this->hydrate($row) : null;
        }

        return array_filter($return);
    }

    public function getTable() : string
    {
        return $this->table;
    }
}
