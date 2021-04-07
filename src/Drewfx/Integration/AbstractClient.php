<?php

namespace Drewfx\Salesforce\Integration;

use Drewfx\Salesforce\Integration\Salesforce\Response;

abstract class AbstractClient implements ClientInterface
{
    protected $body;
    protected $config;
    protected $endpoint;
    protected $environment;
    protected $handler;
    protected $headers;
    protected $method;
    protected $path;
    protected $result;
    protected $ssl;

    public function __construct()
    {
        $this->handler = curl_init();
        $this->setBody();
        $this->setEndPoint();
        $this->setEnvironment();
        $this->setHeaders();
        $this->setMethod(self::METHOD_GET);
        $this->setUseSSL(false);
    }

    public function setUseSSL(bool $ssl = false) : self
    {
        $this->ssl = $ssl;

        return $this;
    }

    public function call(string $path = '', array $parameters = []) : Response
    {
        if ( ! empty($path)) {
            $this->setPath($path);
        }

        if ( ! empty($parameters)) {
            $this->setBody($parameters);
        }

        if ( ! is_resource($this->handler)) {
            $this->handler = curl_init();
        }

        $this->setEndpoint(
            $this->isPost()
                ? sprintf('%s%s', $this->getEndpoint(), $this->getPath())
                : sprintf('%s%s?%s', $this->getEndpoint(), $this->getPath(), http_build_query($this->getBody()))
        );

        curl_setopt($this->handler, CURLOPT_URL, $this->getEndpoint());
        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->handler, CURLOPT_HTTPHEADER, $this->getHeaders());
        curl_setopt($this->handler, CURLINFO_HEADER_OUT, false);

        if ($this->isPost()) {
            curl_setopt($this->handler, CURLOPT_POST, true);
            curl_setopt($this->handler, CURLOPT_POSTFIELDS, $this->getBody());
        }

        if ($this->useSSL()) {
            curl_setopt($this->handler, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($this->handler, CURLOPT_SSL_VERIFYHOST, 2);
        }

        $result = curl_exec($this->handler);
        $code = curl_getinfo($this->handler, CURLINFO_HTTP_CODE);

        if ($result !== false) {
            $this->setResult($result);
        } else {
            $this->setResult(
                curl_error($this->handler)
            );
        }

        $this->reset();

        return new Response($code, $this->getEndpoint(), $this->getResult());
    }

    public function isPost() : bool
    {
        return $this->method === self::METHOD_POST;
    }

    public function getEndpoint() : string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint = '') : self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getPath() : string
    {
        return $this->path;
    }

    public function setPath(string $path) : self
    {
        $this->path = $path ?? $this->path;

        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body = []) : self
    {
        $this->body = $body ?? $this->body;

        return $this;
    }

    public function getHeaders() : array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers = []) : self
    {
        $this->headers = $headers;

        return $this;
    }

    public function useSSL() : bool
    {
        return $this->getUseSSL() === true;
    }

    public function getUseSSL() : bool
    {
        return $this->ssl;
    }

    public function reset() : void
    {
        curl_close($this->handler);
        $this->handler = curl_init();
        $this->setHeaders();
        $this->setBody();
        $this->setMethod();
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result) : self
    {
        if ($result !== false) {
            $this->result = $result;
        }

        return $this;
    }

    public function isGet() : bool
    {
        return $this->method === self::METHOD_GET;
    }

    public function getEnvironment() : string
    {
        return $this->environment;
    }

    public function setEnvironment(string $environment = 'dev') : self
    {
        $this->environment = $environment;

        return $this;
    }

    public function addHeaders(array $headers = []) : self
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    public function getMethod() : string
    {
        return $this->method;
    }

    public function setMethod(string $method = 'get') : self
    {
        $this->method = strtolower($method);

        return $this;
    }
}
