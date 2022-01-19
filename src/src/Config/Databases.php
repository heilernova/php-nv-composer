<?php
namespace PhpNv\Config;

class Databases
{

    public function __construct(private array $databases)
    {
        
    }

    public function getDefaulName():string
    {
        return $this->databases['default'];
    }

    public function getDefaul():array
    {
        return $this->getList()[$this->getDefaulName()];
    }

    public function getDatabase(string $name):?array{
        return $this->getList()[$name] ?? null;
    }

    
    public function getList():array
    {
        return $this->databases['list'];
    }
}