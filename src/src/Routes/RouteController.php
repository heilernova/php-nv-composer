<?php
namespace PhpNv\Routes;

class RouteController
{
    public function __construct(
        public string $url,
        public array $params,
        public $controller,
        public array $canActivate,
        public ?string $method
    )
    {
        
    }
}