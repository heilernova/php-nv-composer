<?php
namespace PhpNv\Data;

use mysqli;
use mysqli_result;
use PhpNv\Error;

use function PhpNv\Http\response;

/**
 * @author Heiler Nova.
 */
class Database
{
    private array $connectionData = array();
    private ?mysqli $connection = null;
    public array $errors = array();
    public int $affectedRows = 0;
    public int $insertId = 0;
    public string $sqlCommand = '';
    public ?array $sqlParamas = null;
    public Query $query;


    public function __construct(string $hostname = null, string $username = null, string $password = null, string $database = null)
    {
        $this->connectionData['hostname'] = $hostname ? $hostname : '';
        $this->connectionData['username'] = $username ? $username : '';
        $this->connectionData['password'] = $password ? $password : '';
        $this->connectionData['database'] = $database ? $database : '';
        
        $this->query = new Query($this);
    }

    private function openConnection()
    {
        if (!$this->connection){
            try {
                
                $data = $this->connectionData;
                $connection = @mysqli_connect($data['hostname'], $data['username'], $data['password'], $data['database']);
                $this->connection = $connection;
                $this->connection->autocommit(false);
                
            } catch (\Throwable $th) {
                $messeage = [
                    'Failed to initialize connection to database',
                    'Connection data',
                    $this->connectionData
                ];
                Error::log($messeage);
            }
        }
    }

    public function commit(){
        $this->connection->commit();
    }

    /**
     * Ejecuta una instrución sql preparada en la base de datos
     * @param string $sql Instrución sql a ejecutar en caso de usar parametros de debe espeficiar con "?"
     * @param array $params Array de los parametros de la consulta slq. el parametro es opcional,
     * en caso de que la cosulta sql no tenga parametros por ingresar.
     * @return mysqli_result|bool
     */
    public function execute(string $sql, ?array $params = null):mysqli_result|bool
    {
        try {
            $this->openConnection();
            $stmt = $this->connection->prepare($sql);
            
            if ($stmt){
                
                if ($params){
                    call_user_func_array(array($stmt, 'bind_param'), $this->getRefValues($params));
                }

                if ($stmt->execute()){

                    $this->affectedRows = $stmt->affected_rows;
                    $this->insertId = $stmt->insert_id;
                    $this->sql_command = $sql;
                    $this->sql_params = $params;

                    $result = $stmt->get_result();

                    return $result ? $result : true;

                }else{

                    $this->errors[] = [
                        'slq'=>$sql,
                        'mensaje'=>$stmt->error
                    ];

                    return false;
                }

            }else{
                $msg = [
                    'Error con la preparación de la base de datos.',
                    'SQL:' . $sql,
                    'Mensaje de error: ' . $this->connection->error 
                ];
                Error::log($msg);
            }

        } catch (\Throwable $th) {

            $msg = [];
            $msg[] = 'Error con la ejecución del metodo Database::execute';
            $msg[] = "Sql: $sql";
            $msg[] = "Paramentros: " . (!$params ? 'null' : '');


            if ($params){
                $prms = []; response($params);
                foreach ($params  as $key => $value){
                    $prms[] = "$key = $value : " . gettype($value);
                }
                $msg[] = $prms;
            }

            $msg[] = "Error: " . $th->getMessage();

            Error::log($msg, $th);
        }
        return false;
    }
    
    private function getRefValues(array $params):array
    {
        $ref = array(''); // Creamos un array con un valor vacio
        
        foreach($params as $key=>$value){
            $ref[0] .= is_string($value) ? 's' : (is_int($value) ? 'i' : 'd');
            $ref[] = &$params[$key];
        }

        return $ref;
    }
}