<?php

namespace Drewfx\Salesforce\Integration;

interface ClientInterface
{
    /** @todo: Extract to HTTP class e.g. Http::OK */
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_ACCEPTED = 202;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    /** @todo: Extract to METHOD class e.g. Method::GET */
    public const METHOD_GET = 'get';
    public const METHOD_POST = 'post';
    public const METHOD_UPDATE = 'update';
    public const METHOD_DELETE = 'delete';
    public const METHOD_PUT = 'put';

    /** @todo: Extract to GRANT_TYPE class e.g. GrantType::Password */
    public const GRANT_TYPE_PASSWORD = 'password';
    public const GRANT_TYPE_AUTHORIZATION = 'authorization_code';
    public const GRANT_TYPE_CLIENT_CREDENTIALS = 'client_credentials';

    /** Register Public Methods */
    public function call();

    public function setBody();

    public function getBody();

    public function setEndpoint();

    public function getEndpoint();

    public function setHeaders();

    public function getHeaders();

    public function setMethod();

    public function getMethod();
}
