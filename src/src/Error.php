<?php
namespace PhpNv;

use PhpNv\Client\ClientInfo;
use Throwable;

class Error
{
    static function log(array $deloper_messages, Throwable $throwable = null,int $response_code = 500,mixed $body = null)
    {
        $path = $_ENV['nv-api-dir-errors'] . 'system-errors.txt';

        $json = [
            'logData'=>[
                'timeZone'=>date_default_timezone_get(),
                'date'=>date('c', time()),
                'viewed'=>false
            ],
            'http'=>[
                'url'=>$_GET['url'],
                'requestMethod'=>$_SERVER['REQUEST_METHOD'],
                'ip'=>ClientInfo::getIp(),
                'device'=>ClientInfo::getDevice(),
                'platform'=>ClientInfo::getPlatform()
            ],
            'messages'=>$deloper_messages,
            'throwable'=>null
        ];
    
        if ($throwable){
            $json['throwable'] = [
                'message'=>$throwable->getMessage(),
                'code'=>$throwable->getCode(),
                'file'=>$throwable->getFile(),
                'line'=>$throwable->getLine(),
                'trace'=>$throwable->getTrace()
            ];
        }

        if (!file_exists($path)){
            fclose(fopen($path, 'w'));
        }


        
        $json = json_encode($json);
        file_put_contents($path, "$json,\n", FILE_APPEND);
        echo "Error";
        http_response_code($response_code);
        exit;
    }
}