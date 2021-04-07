<?php

if ( ! function_exists('dd')) {
    /**
     * Dumps data
     */
    function dd()
    {
        if (function_exists('dump')) {
            array_map(static function ($x) {
                dump($x);
            }, func_get_args());
            die;
        }

        array_map(static function ($x) {
            var_dump($x);
        }, func_get_args());
        die;
    }
}

if ( ! function_exists('base_url')) {
    /**
     * @return string
     */
    function base_url() : string
    {
        $port = in_array($_SERVER['SERVER_PORT'], ['80', '443'], true) ? '' : ':' . $_SERVER['SERVER_PORT'];
        $protocol = ( ! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] === 443 ? 'https' : 'http';

        return $protocol . '://' . $_SERVER['SERVER_NAME'] . $port . '/';
    }
}

if ( ! function_exists('request')) {
    /**
     * @param null $key
     * @return mixed|string
     */
    function request($key = null)
    {
        if (empty($key)) {
            return $_REQUEST;
        }

        if ($key) {
            return isset($_REQUEST[$key]) ? trim($_REQUEST[$key]) : null;
        }

        return null;
    }
}

if ( ! function_exists('session')) {
    /**
     * @param null $key
     * @param null $value
     * @return mixed
     */
    function session($key = null, $value = null) : array
    {
        if ( ! isset($_SESSION)) {
            session_start();
        }

        if ( ! empty($key) && ! empty($value)) {
            $_SESSION[$key] = $value;
        }

        if ( ! empty($key)) {
            return $_SESSION[$key];
        }

        return $_SESSION;
    }
}

if ( ! function_exists('container')) {
    /**
     * Shorthand function to retrieve container instance.
     * @return \Drewfx\Salesforce\Container
     */
    function container() : \Drewfx\Salesforce\Container
    {
        return (new \Drewfx\Salesforce\Plugin)->getContainer();
    }
}

if ( ! function_exists('flatten')) {
    /**
     * @param $array
     * @param string $prefix
     * @return array|mixed
     */
    function flatten($array, $prefix = '') : array
    {
        $result = array();

        foreach ($array as $key => $value) {
            if (is_int($key)) {
                $prefix = '';
            }

            if (is_array($value)) {
                array_merge($result, flatten($value, $prefix . $key . '.'));
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}

if ( ! function_exists("any_empty")) {
    function any_empty() : bool
    {
        return count(array_filter(func_get_args())) !== count(func_get_args());
    }
}
