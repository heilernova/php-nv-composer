<?php
namespace PhpNv\Routes;

use PhpNv\Routing;

class RouteChild
{
    public function __construct(private Route $route)
    {
        
    }


    public static function add(string $url, string|array $controller, ?callable $canActive = null ):Route
    {
        $r = new Route($url, $controller, $canActive);
        // Routing::$listRoutes[] = $r;
        return $r;

    }

    public static function get(string $url, array|callable $controller, ?callable $canActive = null):Route
    {
        $r = new Route($url, $controller, $canActive, 'GET');
        // self::$listRoutes[] = $r;
        return $r;

    }

    public static function post(string $url, array|callable $controller, ?callable $canActive = null):Route
    {
        $r = new Route($url, $controller, $canActive, 'POST');
        // self::$listRoutes[] = $r;
        return $r;

    }

    public static function delete(string $url, array|callable $controller, ?callable $canActive = null):Route
    {
        $r = new Route($url, $controller, $canActive, 'DELETE');
        // self::$listRoutes[] = $r;
        return $r;

    }

    public static function patch(string $url, array|callable $controller, ?callable $canActive = null):Route
    {
        $r = new Route($url, $controller, $canActive, 'PATCH');
        // self::$listRoutes[] = $r;
        return $r;

    }
}