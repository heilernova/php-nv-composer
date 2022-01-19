<?php
namespace PhpNv\Config;

class Application
{

    public function __construct(private array $application)
    {
        
    }

    public function getName():string{
        return $this->application['name'];
    }

    public function getDescription():string
    {
        return $this->application['description'];
    }
}