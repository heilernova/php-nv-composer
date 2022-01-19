<?php
namespace PhpNv\Config;

class Debug
{
    public function __construct(private array $debug)
    {
        
    }

    public function getPath():string
    {
        return $this->debug['path'] ?? '';
    }

    public function getUsername():string
    {
        return $this->debug['access']['username'] ?? '';
    }

    public function getPassword():string
    {
        return $this->debug['access']['password'] ?? '';
    }
}
