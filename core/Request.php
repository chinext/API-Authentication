<?php

namespace App\Core;

use App\Core\App;

class Request
{
    /**
     * Fetch the request URI.
     *
     * @return string
     */
    public static function uri()
    {
        return str_replace(App::get('folder'), '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    }
    
    /**
     * Fetch the request method.
     *
     * @return string
     */
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
