<?php

namespace Drewfx\Salesforce\Exception;

use Exception;

class GatewayServiceException extends Exception
{
    public static function responseError($message) : GatewayServiceException
    {
        return new static(
            sprintf('Error: Gateway Service returned an error: %s', $message)
        );
    }
}
