<?php

namespace Drewfx\Salesforce\Integration\Salesforce;

use function get_option;

class Configuration
{
    public const CONFIG_NAME = 'salesforce_integration_settings';

    /** @var array */
    protected static $fields;

    public static function get(string $field = '') : ?array
    {
        if ( ! isset(self::$fields)) {
            self::$fields = get_option(self::CONFIG_NAME);
        }

        if ( ! $field) {
            return self::$fields;
        }

        return self::$fields[$field] ?? null;
    }
}
