<?php

namespace Drewfx\Salesforce\Model;

class QueueItem extends AbstractModel
{
    protected $keys = [
        'attempts', 'message', 'fields', 'created_at'
    ];

    protected $id;
    protected $attempts;
    protected $message;
    protected $fields;
    protected $created_at;

    public function incrementAttempts() : void
    {
        $this->attempts = ++$this->attempts;
    }

    public function getAttempts()
    {
        return $this->attempts;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage(?string $message) : QueueItem
    {
        $this->message = $message;

        return $this;
    }

    public function getFields(bool $decode = false) : array
    {
        if ($decode === true) {
            try {
                return json_decode($this->fields, true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                return [];
            }
        }

        return $this->fields;
    }

    public function setFields($fields) : self
    {
        try {
            $this->fields = json_encode($fields, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->fields = [];
        }

        return $this;
    }

    public function getCreatedAt() : string
    {
        return $this->created_at;
    }

    public function setCreatedAt() : self
    {
        $this->created_at = date('Y-m-d H:i:s', time());

        return $this;
    }
}
