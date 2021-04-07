<?php

namespace Drewfx\Salesforce\Model;

class Token extends AbstractModel
{
    public const INACTIVE = 0;
    public const ACTIVE = 1;

    public $instance_url;
    public $token_type;
    public $access_token;
    public $active;
    public $signature;
    public $issued_at;
    public $created_at;

    protected $keys = [
        'instance_url', 'token_type', 'access_token', 'active',
        'signature', 'issued_at', 'created_at'
    ];
    protected $id;

    public function getInstanceUrl() : string
    {
        return $this->instance_url;
    }

    public function setInstanceUrl(string $instance) : Token
    {
        $this->instance_url = $instance;

        return $this;
    }

    public function getInstanceHost() : string
    {
        return preg_replace('#^https?://#', '', $this->instance_url);
    }

    public function getTokenType() : string
    {
        return $this->token_type;
    }

    public function setTokenType(string $type) : self
    {
        $this->token_type = $type;

        return $this;
    }

    public function getAccessToken() : string
    {
        return $this->access_token;
    }

    public function setAccessToken(string $token) : self
    {
        $this->access_token = $token;

        return $this;
    }

    public function getActive() : bool
    {
        return (bool) $this->active;
    }

    public function setActive($active) : self
    {
        $this->active = $active;

        return $this;
    }

    public function getSignature() : string
    {
        return $this->signature;
    }

    public function setSignature(string $signature) : self
    {
        $this->signature = $signature;

        return $this;
    }

    public function getIssuedAt() : string
    {
        return $this->issued_at;
    }

    public function setIssuedAt($issuedAt) : self
    {
        $this->issued_at = date('Y-m-d H:i:s', substr($issuedAt, 0, -3));

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
