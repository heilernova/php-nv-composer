<?php
namespace PhpNv;

use PhpNv\Routes\Route;
use PhpNv\Routes\RouteController;

use function PhpNv\Http\response;

/**
 * @author Heiler Nova.
 */
class Routing
{
    private static array $listRoutes = [];

    /**
     * @param string $url
     * @param string|array<string,string> $controller
     */
    public static function add(string $url, string|array $controller, ?callable $canActive = null ):Route
    {
        $r = new Route($url, $controller, $canActive);
        self::$listRoutes[] = $r;
        return $r;

    }

    public static function get(string $url, array|callable $controller, ?callable $canActive = null):Route
    {
        $r = new Route($url, $controller, $canActive, 'GET');
        self::$listRoutes[] = $r;
        return $r;

    }

    public static function post(string $url, array|callable $controller, ?callable $canActive = null):Route
    {
        $r = new Route($url, $controller, $canActive, 'POST');
        self::$listRoutes[] = $r;
        return $r;

    }

    public static function delete(string $url, array|callable $controller, ?callable $canActive = null):Route
    {
        $r = new Route($url, $controller, $canActive, 'DELETE');
        self::$listRoutes[] = $r;
        return $r;

    }

    public static function patch(string $url, array|callable $controller, ?callable $canActive = null):Route
    {
        $r = new Route($url, $controller, $canActive, 'PATCH');
        self::$listRoutes[] = $r;
        return $r;

    }

    /**
     * @return array<Route>
     */
    private static function getRoutes():array
    {
        return self::$listRoutes;
    }

    public static function find(string $http_url):null|RouteController
    {
        $routes = self::getRoutes();

        uasort($routes, function($a, $b){ return (strcmp($b->url, $a->url)); });

        $url_items = explode('/', $http_url);
        $url_items_num = count($url_items);
        
        $filter_routes =  array_filter($routes, function(Route $route) use ($url_items, $url_items_num, $http_url){
            
            if ($route->urlItemsNum == $url_items_num){
    
                $valid = true;
                foreach ($route->indexControllers as $index) {
    
                    if ($route->urlItems[$index] == $url_items[$index]){
                        $route->httpRequest = $http_url; 
                        // $valid = true;
    
                    }else{
                        $valid = false;
                    }
                }
    
                return $valid;
    
            }else{
    
                return false;
    
            }
    
        });

        uasort($filter_routes, function($a, $b){ return (strcmp($b->numController, $a->numController)); });
        $route = array_shift($filter_routes);

        if ($route){
            $route= $route->getHttp();
        }

        return $route;
    }
}