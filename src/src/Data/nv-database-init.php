<?php
namespace PhpNv\Data;

use function PhpNv\Http\response;

function nv_database_init(string $database_name = null):Database
{
    $database = $database_name ? $database_name : $_ENV['nv-api-databases']['default'];//response([$database, $_ENV['nv-api-databases']['list'][$database]]);


    if (isset($_ENV['nv-api-databases']['list'][$database])){

        $data = $_ENV['nv-api-databases']['list'][$database];

        return new Database($data['hostname'], $data['username'], $data['password'], $data['database']);
    }else{
        response('Error database dafult', 500);
    }
}