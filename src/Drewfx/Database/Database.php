<?php

namespace Drewfx\Salesforce\Database;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    public const MYSQL = 'mysql';
    public const MSSQL = 'sqlsrv';

    public const MYSQL_FORMAT = '%s:host=%s;dbname=%s;charset=utf8';
    public const MSSQL_FORMAT = '%s:Server=%s;Database=%s;';

    /**
     * @var PDO
     */
    protected $connection;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $dsn;

    /**
     * @var string
     */
    protected $format;

    /**
     * @var PDOStatement
     */
    protected $statement;

    public function __construct()
    {
        $this->loadDefault();

        if ( ! $this->connection) {
            $this->connect();
        }
    }

    protected function loadDefault() : void
    {
        $this->setAttributes([
            'dsn' => self::MYSQL,
            'host' => DB_HOST,
            'name' => DB_NAME,
            'user' => DB_USER,
            'password' => DB_PASSWORD
        ]);
    }

    protected function setAttributes(array $config = []) : void
    {
        $this->dsn = $config['dsn'];
        $this->host = $config['host'];
        $this->name = $config['name'];
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->format = $this->setFormat();

        $this->connect();
    }

    protected function setFormat() : string
    {
        return $this->isMySQL() ? self::MYSQL_FORMAT : self::MSSQL_FORMAT;
    }

    public function isMySQL() : bool
    {
        return $this->dsn === self::MYSQL;
    }

    public function connect() : Database
    {
        try {
            $this->connection = new PDO($this->connectionString(), $this->user, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw $e;
        }

        return $this;
    }

    private function connectionString() : string
    {
        return sprintf(
            $this->format,
            $this->dsn,
            $this->host,
            $this->name
        );
    }

    public function select($fields, $table) : Database
    {
        $this->statement = $this->connection->query(sprintf('select %s from %s', $fields, $table));

        return $this;
    }

    public function query($sql, array $parameters = []) : Database
    {
        $this->statement = $this->connection->prepare($sql);
        $this->statement->execute($parameters);

        return $this;
    }

    public function insert($sql, array $parameters) : Database
    {
        $this->statement = $this->connection->prepare($sql)->execute($parameters);

        return $this;
    }

    public function delete($table, $column = 'id', $operator = '=', $value = '') : Database
    {
        $this->statement = $this->connection->prepare(
            sprintf('delete from %s where %s %s %s', $table, $column, $operator, $value)
        )->execute();

        return $this;
    }

    public function raw($sql) : string
    {
        $count = $this->statement = $this->connection->exec($sql);

        return $count . ' rows modified.';
    }

    public function first()
    {
        $result = $this->statement->fetch(PDO::FETCH_ASSOC);

        return $result === false ? null : $result;
    }

    public function all() : array
    {
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function reset() : void
    {
        $this->statement = null;
    }

    public function close() : void
    {
        $this->connection = null;
    }

    public function change(array $config = []) : void
    {
        $this->setAttributes($config);
    }

    public function isMSSQL() : bool
    {
        return $this->dsn === self::MSSQL;
    }
}
