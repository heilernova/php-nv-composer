<?php
namespace PhpNv\Http;

class HttpBodyRespose
{
    public function __construct(
        public bool $status = false,
        public int $statusCode = 0,
        public array $messages = [],
        public $data = null
    )
    {
        
    }
}