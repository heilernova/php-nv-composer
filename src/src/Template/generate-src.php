<?php
namespace PhpNv\Template;

function generate_src($dir, $name, $namespace)
{
    $dir_src = mkdir("$dir/$name");

    $settings_json = [];
    $settings_json['application']['name'] = $name;
    $settings_json['application']['description'] = "";
    
    $settings_json['debug']['path'] = "errors";
    $settings_json['debug']['access'] = ["username"=>"root", "password"=>"admin"];

    $settings_json['namespace'] = "$name";

    $settings_json['databases']['default'] = 'default';
    $settings_json['databases']['list'] = ["hostname"=>"", "username"=>"", "password"=>"", "database"=>""];

    $file = fopen("$dir/$name/$name.settings.json", 'w');
    fputs($file, json_encode($settings_json, JSON_PRETTY_PRINT));
    fclose($file);

    // Cramos el fichero de rutas
    $file = fopen("$dir/$name/$name.ruter.php", 'w');
    fputs($file, "<?php\nnamespace Api\\Https;\n\nuse PhpNv\Routing;\n\n");
    fclose($file);

    // creamos el fichero index.php
    $file = fopen("$dir/$name/$name.index.php", 'w');
    fputs($file, "<?php\n");
    fputs($file, "// Archivo de ejecucion de la aplicacion\n\n// requirimo el archivo autoload\n");
    fputs($file, "require_once __DIR__ . " . "'/../vendor/autoload.php';" ."\n");
    fputs($file, "require_once 'Api.ruter.php';" ."\n\n\n");

    fputs($file, '$main = new PhpNv\Main(__DIR__ . '.  "'/$name.settings.json'"  .');');
    fputs($file,  "\n" . '$main->run($_GET["url"]);');
    fclose($file);
}