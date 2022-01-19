<?php
namespace PhpNv\Http;

use PhpNv\Error;


/**
 * @author Heiler Nova.
 */
class HttpBody
{
    public function __construct(array|null $data_body)
    {
        if ($data_body){
            $keys_undefine = [];
            $keys_invalid = [];

            foreach ($this as $key=>$value){
                if (array_key_exists($key, $data_body)){
                    try {
                        $this->$key = $data_body[$key];
                    } catch (\Throwable $th) {
                        $keys_invalid[] = "$key:" . gettype($this->$key) . ' - Data body type: ' . gettype($data_body[$key]);
                    }
                }else{
                    $keys_undefine[] = $key;
                }
            }

            if (count($keys_undefine) > 0 || count($keys_invalid)){
                $message = [
                    'Parametros del body no concuerda con el body',
                    'Parametros indefinidos.',
                    $keys_undefine,
                    'Parametros invalidos',
                    $keys_invalid
                ];
                $body = "The parameter of the body object do not match the requesteds ones";
                Error::log($message, null, 400, $body);
            }
            
        }else{
            response('Http Request requests data in body', 400);
        }
    }
}