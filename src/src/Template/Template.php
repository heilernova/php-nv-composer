<?php
namespace PhpNv\Template;

require_once  'generate-www.php';
require_once  'generate-src.php';
require_once  'composer-update.php';

class Template
{
    private static array $composerJSON;
    private static string $dir;

    private static function getprojectDafult():array{
        return self::$composerJSON['nv-code']['projects'][ self::$composerJSON['nv-code']['default']];
    }

    public static function initApi(string $dir){
        self::createProyect($dir, 'Api', 'Api');
    }

    public static function setComposerJSON(array $data, $dir){
        self::$composerJSON = $data;
        self::$dir = $dir;
    }

    public static function createProyect(string $dir, string $name = null, $namespace)
    {
        generate_src($dir, $name, $namespace);
        generate_www($dir, "../$name/$name.index.php");
        composer_update($dir, $namespace, $name);
        echo "$dir/$name.json";
    }

    public static function createController(string $name_controller, string $url = null){
        self::validInit();
        $name_controller = strtolower(str_replace('.', '-', $name_controller));
        $name_controller = str_replace('controller', '', $name_controller);

        $name_words = explode('-', $name_controller);

        $name = '';
        foreach ($name_words as $item){
            $name .= ucfirst($item);
        };

        if (!file_exists( self::getprojectDafult()['src'] . "Https/")) mkdir( self::getprojectDafult()['src'] . "Https/");

        $file_path = self::getprojectDafult()['src'] . "Https/". $name . "Controller.php";
        
        if (file_exists($file_path)){
            echo "El controlador ya existe";
        }else{

            $namespace = self::getprojectDafult()['namespace'];

            $file = fopen($file_path, 'a');
            $name .= "Controller";
            fputs($file, "<?php\n");
            fputs($file, "namespace $namespace\\Https;\n\n");
            fputs($file, "use PhpNv\Http\HttpController;\n\n");
            fputs($file, "use function PhpNv\\Http\\response;\n\n");
            fputs($file, "class $name extends HttpController\n{");
                fputs($file, "\n\n\tfunction get(){\n\n\t\tresponse('ok - get');\n\t}");
                fputs($file, "\n\n\tfunction post(){\n\n\t\tresponse('ok - post');\n\t}");
                fputs($file, "\n\n\tfunction put(){\n\n\t\tresponse('ok - put');\n\t}");
                fputs($file, "\n\n\tfunction delete(){\n\n\t\tresponse('ok - delete');\n\t}");
                fputs($file,"\n}");

            echo "\nControlador creador.\n";
            echo  $file_path;

            if ($url){
                $file = fopen(self::getprojectDafult()['src'] . "Api.ruter.php", 'a+');
                fputs($file,  "\nRouting::add('$url', $name::class );");
            }
        }
        
    }

    private static function validInit(){

        if (!array_key_exists('nv-code',self::$composerJSON)){
            echo "No se iniciado php nv"; exit;
        }
    }

}