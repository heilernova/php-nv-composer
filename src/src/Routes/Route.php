<?php
namespace PhpNv\Routes;

use PhpNv\Error;
use PhpNv\Routes\Route as RoutesRoute;
use ReflectionFunction;
use ReflectionMethod;

use function PhpNv\Http\response;

/**
 * @author Heiler Nova.
 */
class Route
{
    private static array $ruotes = [];

    public string   $url = '';
    public int      $urlItemsNum = 0;
    public array    $urlItems = [];

    public ?string   $httpMethod = null;
    public string   $httpRequest = '';

    public $controller;

    public array    $indexControllers = [];
    public array    $indexParams = [];

    public array $canActive = [];

    public array $chilRoutes = [];

    public int $numController = 0;

    public function __construct(string $url, string|callable|array $controller, ?callable $canActive = null, $method = null)
    {
        $this->url = $url;
        $this->urlItems = explode('/', $url);
        $this->urlItemsNum = count($this->urlItems);

        $this->controller = $controller;

        if ($canActive) $this->canActive[] = $canActive;
        $this->httpMethod = $method;

        // We set the parameters indices and controller identifiers
        $i = 0;
        foreach ($this->urlItems as $item){
            if (str_starts_with($item, '{')){
                $items = explode(':', trim($item, "{\}"));
                $this->indexParams[] = [$i, ['name'=>$items[0], 'type'=>$items[0] ?? 'mixed']];
            }else{
                $this->indexControllers[] = $i;
            }

            $i++;
        }
        $this->numController = count($this->indexControllers);
    }

    /**
     * Obtiene los parametros de la url de la peticion http.
     */
    private function getHttpParams():array
    {
        $url = $this->httpRequest;
        $url_items = explode('/', $url);
        $params = [];

        foreach($this->indexParams as $item){
            $index = $item[0];
            $name = $item[1]['name'];
            $type = $item[1]['type'];

            $params[$name] = match ($type){
                'int'   =>(int)$url_items[$index],
                'float' =>(float)$url_items[$index],
                default => $url_items[$index]
            };
        }
        return $params;
    }

    public function getHttp():RouteController{

        return new RouteController(
            $this->httpRequest, 
            $this->getHttpParams(), 
            $this->controller,
            $this->canActive,
            $this->httpMethod,
        );
    }

    public function execute():void
    {
        // 

        if ($this->canActive){
            $canActive = $this->canActive;

            // $ref = new ReflectionMethod($canActive);
            
            $canActive();

        }
        
        if (is_callable($this->controller)){
            $calleble = $this->controller;

            if ($this->httpMethod == $_SERVER['REQUEST_METHOD']){
                $re = new ReflectionFunction($calleble);
                $params = $this->getHttpParams();
                $re->invokeArgs($params);
            }else{
                response('Method not allowed for URL', 405);
            }
            
        }else{
            $namespace = '';
            $method = '';

            if (is_array($this->controller)){
                $namespace = $this->controller[0];
                $method = $this->controller[1];
            }else{

                $namespace = $this->controller;
            }

            $controller = new $namespace();

            $method = strtolower($_SERVER['REQUEST_METHOD']) . ucfirst($method);

            if (method_exists($controller, $method)){
                
                $ref_method = new ReflectionMethod($controller, $method);

                try {
                    $ref_method->invokeArgs($controller, $this->getHttpParams());
                } catch (\Throwable $th) {
                    Error::log(['Error de parametros de url'], $th);
                }

            }else{
                response('Method not allowed for URL', 405);
            }
        }
    }

}