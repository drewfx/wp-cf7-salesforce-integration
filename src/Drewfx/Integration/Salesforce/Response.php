<?php

namespace Drewfx\Salesforce\Integration\Salesforce;

class Response
{
    protected $attributes;
    protected $code;
    protected $errors;
    protected $message;
    protected $raw;
    protected $url;

    /**
     * Response constructor.
     * @param $code
     * @param $url
     * @param $result
     */
    public function __construct($code, $url, $result)
    {
        $this->setUrl($url);
        $this->setCode($code);
        $this->parse($result);
    }

    protected function parse($result) : Response
    {
        $this->setRaw($result);

        $json = flatten(
            json_decode($result, true)
        );

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->malformed();
        }

        if (in_array($this->getCode(), [401, 403], true)) {
            $this->setMessage($json['message']);
            $this->setErrors($json);

            return $this;
        }

        $this->setMessage($json['message'] ?? null);
        $this->setErrors($json['errors'] ?? []);
        $this->setAttributes($json);

        return $this;
    }

    protected function malformed() : Response
    {
        $this->code = null;
        $this->message = json_last_error_msg();
        $this->errors = [
            'parse_error' => [
                'code' => json_last_error(),
                'message' => json_last_error_msg()
            ]
        ];

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code) : Response
    {
        $this->code = (int) $code;

        return $this;
    }

    public function isValid() : bool
    {
        return in_array(
            $this->getCode(),
            [Client::HTTP_OK, Client::HTTP_CREATED, Client::HTTP_ACCEPTED],
            true
        );
    }

    public function isUnauthorized() : bool
    {
        return $this->code === Client::HTTP_UNAUTHORIZED;
    }

    public function isForbidden() : bool
    {
        return $this->code === Client::HTTP_FORBIDDEN;
    }

    public function hasAttribute($key) : bool
    {
        return isset($this->attributes[$key]);
    }

    public function getAttribute($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes) : array
    {
        return $this->attributes = $attributes;
    }

    public function hasErrors() : bool
    {
        return ! empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setErrors($errors) : Response
    {
        $this->errors = $errors;

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message) : Response
    {
        $this->message = $message;

        return $this;
    }

    public function getRaw()
    {
        return $this->raw;
    }

    public function setRaw($raw) : Response
    {
        $this->raw = $raw;

        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url) : void
    {
        $this->url = $url;
    }
}
