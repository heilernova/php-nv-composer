<?php
namespace PhpNv\Scripts;

use PhpNv\Template\Template;

class Proyect
{
    public static function execute($event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        require $vendorDir . '/autoload.php';

        $dir = dirname($vendorDir); // Direcctorio raiz del vendor

        $params = $event->getArguments();

        if ($params){
            Template::setComposerJSON(json_decode(file_get_contents("$dir/composer.json"), true), $dir );

            switch ($params[0]) {
                case 'init':
                //    Template::createProyect($dir, $params[1] ?? 'app', $params[2] ?? 'app');
                    Template::initApi($dir);
                    echo "\n - app crada correctamente\n";
                    echo "** http://localhost/"  . basename($dir) . "/www/ **";
                    echo "\n.";
                    break;
               
                case 'g':
                    if (isset($params[1])){
                        switch ($params[1]) {
                            case 'c':
                                // Generamos un controlador
                                if (isset($params[2])){
                                    Template::createController($params[2], $params[3] ?? null);
                                }
                                else
                                    echo "Falta el parametro nombre del controlador";
                                break;
                            
                            default:
                                # code...
                                break;
                        }
                    }else{
                        nv_commands_long();
                    }
                    break;
                case 'load':
                    echo "dump-autoload";
                    break;
                default:
                    nv_commands_long();
                   break;
            }

        }else{
            
            nv_commands_long();
            
        }
    }
}

function nv_commands_long(){
    echo "\n--------------------------------------------------------------------------------------------\n";
    echo "-------------------------------------NOVA | CODE--------------------------------------------\n";
    echo "------------------------------ http://www.nv-code.com --------------------------------------\n";
    echo "--------------------------------------------------------------------------------------------\n";
    echo "                       No se ingreseo ningun comando de acción\n";
    echo "--------------------------------------------------------------------------------------------\n";

    echo "\nLista de comandos: \n";
    echo "\t01: nv init                                     => Crae el projecto Api - namespaces sera api\n";
    echo "\t01: nv c <name_app> <name_space of class>       => Crae un proyecto de api\n";
    echo "\t02: nv g c <name_comntroller>                   => Crea un controlador http rest\n";
    echo "\t03: nv g m <name_model>                         => Crea un model\n";
    echo "\t04: nv g g <name_guard>                         => Crea un guard para protejer rutas\n";

    echo "\n.";
}