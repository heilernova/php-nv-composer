<?php
namespace PhpNv;

use App\Http\AuthController;
use PhpNv\Routes\Route;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionProperty;

use function PhpNv\Http\response;

class Controller
{
    private static Route $route;

    public static function execute(string $url)
    {
        
        $route = Routing::find($url); 

        if ($route){
            $method_http = $_SERVER['REQUEST_METHOD'];

            
            // response($canActives);
            foreach($route->canActivate as $ac){
                $ac();
            }

            if (is_callable($route->controller)){
                if ($route->method == $method_http){
                    


                    $ref = new ReflectionFunction($route->controller);
                    $ref->invokeArgs($route->params);

                }else{
                    response('Method not allowed for URL', 405);
                }
            }else{

                $controller_namespace = '';
                $controller_method = null;

                if (is_array($route->controller)){
                    $controller_namespace = $route->controller[0];
                    $controller_method = $route->controller[1] ?? null;
                    
                }else{
                    $controller_namespace = $route->controller;
                }


                if ($route->method){
                    if ($route->method != $method_http){
                        response('Method not allowed for URL', 405);
                    }else{
                        $controller_method = $controller_method ? $controller_method : $method_http;
                    }
                }else{
                    if ($controller_method){
                        $controller_method = $method_http . $controller_method;
                    }else{
                        $controller_method = $method_http;
                    }
                }
                
                
                $object_controller = new $controller_namespace();
                // response($controller_namespace);

                if (method_exists($object_controller, $controller_method)){
                    $ref = new ReflectionMethod($object_controller, $controller_method);
                    $ref->invokeArgs($object_controller, $route->params);
                }else{
                    response('Method not allowed for URL', 405);
                }
            }

        }else{
            response('not controller', 404);
        }
    }

}