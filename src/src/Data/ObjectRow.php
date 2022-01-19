<?php
namespace PhpNv\Data;

use PhpNv\Error;

/**
 * @author Heiler Nova
 */
class ObjectRow
{

    /**
     * Inicializa el objeto cargado los datos ingresado a la clase.
     * @param array  $data_row Array asociativo de los parametros a cargar en la clase
     * @param bool $strict Estable si la carga debe ser estricta con lo valores solicitados.
     */
    public function __construct(array $data_row, bool $strict = true)
    {
        $keys_undefine = [];
        $keys_invalid = [];

        foreach ($this as $key=>$value){
            if (array_key_exists($key, $data_row)){
                try {
                    $this->key = $data_row[$key];
                } catch (\Throwable $th) {
                    $keys_invalid[] = "$key : " . gettype($this->$key) . " => " . gettype($data_row[$key]);            
                }
            }else{
                $keys_undefine[] = $key;
            }
        }

        if ($strict){
            if ($keys_invalid || $keys_undefine){
                $message = [
                    'Failed to initailze object: ' . $this::class,
                    'Keys undefine',
                    $keys_undefine,
                    'Keys invalid',
                    $keys_invalid
                ];

                Error::log($message);
            }
        }else{
            if ($keys_invalid){
                $message = [
                    'Failed to initailze object: ' . $this::class,
                    'Keys invalid',
                    $keys_invalid
                ];
                Error::log($message);
            }
        }
    }
}