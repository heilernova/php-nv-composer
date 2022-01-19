<?php
namespace PhpNv\Http;

use PhpNv\Data\Database;

class HttpModel
{
    public function __construct(protected Database $database)
    {
            
    }
}