<?php
namespace PhpNv;

use PhpNv\Error\ErrorController;
use PhpNv\nv\nv;
use PhpNv\Routes\Route;

use function PhpNv\Http\response;

class Main
{
    private array $routes = [];

    private ?string $origins = null;
    private ?string $headers = null;
    private ?string $methods = null;
    private string $timezone = 'UTC';

    public function __construct(string $path_settings_json, string $api_name = null)
    {
        header('content-type: application/json');

        $json = json_decode(file_get_contents($path_settings_json), true);

        nv::init($json);

        $_ENV['nv-api-databases']['default'] = $json['databases']['default'];
        $_ENV['nv-api-databases']['list'] = $json['databases']['list'];

        $_ENV['nv-api-name'] = $api_name ? $api_name : '';

        $_ENV['nv-api-dir-errors'] = '../errors/';//$settings_json['dirErrors'];

        $_ENV['nv-name-proyect'] = $json['application']['name'];
    }

    private function listItems(array $items):string
    {
        $list = array_reduce($items, function(string $carry, string $item){
            $carry .= ", $item";
            return $carry;
        });

        return ltrim($list, ', ');
    }


    /**
     * 
     * 
     * Establece el nombre personalizado de la api
     * 
     * 
     * 
     */
    public function setApiName(string $name):void
    {
        $_ENV['nv-api-name'] = $name;
    }

    public function setDefaultTimezone(string $timezone):void
    {
        $this->timezone = $timezone;
    }

    /**
     * 
     * 
     * Estable las cabecerea permitidas al cliente
     * @param string|array $headers 
     *
     *
    */
    public function setHeaders(string|array $headers):void
    {
        if (is_array($headers)) $headers = $this->listItems($headers);
        $this->headers = $headers;
    }

    /**
     * Establce los oriqines que tiene acceso a las peticiones HTTP
     */
    public function setOrigin(string|array $origin):void
    {
        if (is_array($origin)) $origin = $this->listItems($origin);
        $this->origins = $origin;
    }

    /**
     * Establce los método que soporta las peticiones HTTP
     */
    public function setMethods(string|array $methods):void
    {
        if (is_array($methods)) $this->listItems($methods);
        $this->methods = $methods;
    }

    /**
     * Ejecuta la aplicacion web
     */
    public function run(string $url):void
    {
        $url = trim($url, "/");

        date_default_timezone_set($this->timezone);

        Routing::get('nv-errors/list', function(){ ErrorController::get(); });
        Routing::delete('nv-errors/list/all', function(){ ErrorController::deleteAll(); });
        Routing::delete('nv-errors/list/{id:int}', function($id){ ErrorController::delete($id); });

        
        if ($this->origins) header("Access-Control-Allow-Origin: $this->origins");
        if ($this->headers) header("Access-Control-Allow-Headers:$this->headers");
        if ($this->methods) header("Access-Control-Allow-Methods: $this->methods");
        // ------------------------ CORS

        if (isset($_SERVER['HTTP_Origin'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_Origin']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        
        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: *");
        
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            exit(0);
        }

        

        if (empty($url)){
            header("Content-Type: text/html; charset=utf-8");
            require 'Views/index.php';
        }else{
           Controller::execute($url);
        }
    }
}