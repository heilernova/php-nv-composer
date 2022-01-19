<?php
namespace PhpNv\Http;

use PhpNv\Data\Database;

use function PhpNv\Data\nv_database_init;

/**
 * @author Heiler Nova
 */
class HttpController
{
    public Database $database;

    public function __construct()
    {
        $this->database = nv_database_init();
    }

    /**
     * @return array retorna un array del JSON enviado por el cliente en el body
     */
    protected function getBody():array{
        return json_decode(file_get_contents('php://input'), true);
    }
}