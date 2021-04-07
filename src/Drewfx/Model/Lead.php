<?php

namespace Drewfx\Salesforce\Model;

class Lead extends AbstractModel
{
    protected $keys = [
        'url', 'request', 'response',
        'code', 'message', 'created_at'
    ];

    protected $id;
    protected $url;
    protected $request;
    protected $response;
    protected $code;
    protected $message;
    protected $created_at;

    public function getUrl() : string
    {
        return $this->url;
    }

    public function setUrl($url) : self
    {
        $this->url = $url;

        return $this;
    }

    public function getRequest() : string
    {
        return $this->request;
    }

    public function setRequest($request) : self
    {
        $this->request = $request;

        return $this;
    }

    public function getResponse() : string
    {
        return $this->response;
    }

    public function setResponse($response) : self
    {
        $this->response = $response;

        return $this;
    }

    public function getCode() : int
    {
        return $this->code;
    }

    public function setCode($code) : self
    {
        $this->code = (int) $code;

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message) : self
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt() : string
    {
        return $this->created_at;
    }

    public function setCreatedAt() : self
    {
        $this->created_at = date('Y-m-d H:i:s');

        return $this;
    }
}
