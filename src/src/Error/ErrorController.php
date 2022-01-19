<?php
namespace PhpNv\Error;

use function PhpNv\Http\response;

class ErrorController
{
    public static function get()
    {
        $dir = $_ENV['nv-api-dir-errors'];
        $text = trim(file_get_contents($dir . 'system-errors.txt'), ",\n");
        response(json_decode("[$text]"));
    }

    public static function autenticate(){
        $data = json_decode(file_get_contents('php://input'), true);

    }

    public static function delete(int $id){
        $dir = $_ENV['nv-api-dir-errors'] . 'system-errors.txt';
        $text = trim(file_get_contents($dir), "\n");

        $lines = explode("\n",$text);
        
        array_splice($lines, $id, 1);

        $file = fopen($dir, 'w+');

        foreach($lines as $l){
            fputs($file, "$l\n");
        }
        fclose($file);

        response(true);
    }

    public static function deleteAll(){
        unlink( $_ENV['nv-api-dir-errors'] . 'system-errors.txt');
        fopen( $_ENV['nv-api-dir-errors'] . 'system-errors.txt', 'w');
    }
}
