<?php
namespace PhpNv\Data;

use mysqli_result;

use function PhpNv\Http\response;

/**
 * @author Heiler Nova.
 */
class Query
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Ejecuta un insert en la base de datos.
     * @param array $params array asosiativo de los valor de inserción
     * @param string $table Nombre de la tabla a la cual se le insertaran los datos.
     */
    public function insert(array $params, string $table):bool
    {
        $fields = '';
        foreach (array_keys($params) as $key) $fields .= ", $key";
    
        $fields = ltrim($fields, ', ');
        $values = ltrim(str_repeat(", ?", count($params)), ", ");

        $sql = "INSERT INTO $table($fields) VALUES($values)";
        // response($sql);
        return $this->database->execute($sql, (array)$params);
    }
    /**
     * Ejecuta un comando update en la base de datos.
     * @param array $params Array de los valores a cambiar, la key del item hace referencia al
     * campo de la base de datos,
     * @param array $condition Array de la condición en primere item de array en la condición,
     * y el segundo lo parametros ejm.  ['name=?', ['heiler']]
     * @return bool
     */
    public function update(array $params, array $condition, $table):bool
    {
        $values = array_reduce(array_keys($params), function($carry, $item){
            $carry .= ", $item=?";
            return $carry;
        });
    
        $values = ltrim($values, ", ");
    
        $sql =  "UPDATE $table SET $values WHERE  $condition[0]";

        $sql_params = array_merge($params, $condition[1] ?? []);

        return $this->database->execute($sql,$sql_params);
    }

    /**
     * Ejecuta un delete en la base de datos
     * @param string $condition Condicion where para eliminar datos
     * @param array $params array de los parametros de la condición.
     * @param string $table Nombre de la tabla donde se ejecutara la eliminación.
     * @return bool Retorna el resulta de los consulta sql.
     */
    public function delete(string $condition, array $params, string $table):bool
    {
        return $this->database->execute("DELETE FROM $table WHERE $condition", $params);
    }

    /**
     * Ejecuta el llamado de una función en la base de datos.
     * @param string $name Nombre de la función
     * @param array|null $params parametros de la función
     * @return mixed Retorna el valor resultante de ejecutar la función.
     */
    public function function(string $name, ?array $params = null):mixed
    {
        $sql = "SELECT $name(" . (ltrim(str_repeat(', ?',  ($params ? count($params) : 0),', '))) .")";
        return $this->database->execute($sql, $params)->fetch_array()[0]; 
    }


    /**
     * Ejecuta el llamado de un procedimiento en la base  de datos.
     * @param string $name Nombre del procedimiento
     * @param array $params Array de los parametros en la base de datos.
     */
    public function procedure(string $name, ?array $params = null):mysqli_result|bool
    {
        $sql = "CALL $name(" . (ltrim(str_repeat(', ?',  ($params ? count($params) : 0),', '))) .")";
        return $this->database->execute($sql, $params); 
    }
}