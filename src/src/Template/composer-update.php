<?php
namespace PhpNv\Template;

function composer_update($dir, $namespace, $src){

    $json = json_decode(file_get_contents("$dir/composer.json"), true);

    $json['autoload']['psr-4']["$namespace\\"] = "$src/";

    if (!$json['require']) $json['require'] = (object)[];

    if (!array_key_exists('nv-code', $json)) $json['nv-code'] = ['default'=> $src, 'projects'=>[]];

    $json['nv-code']['default'] = $src;
    $json['nv-code']['projects'][$src] = [
        'src'=>"$src/",
        'namespace'=>$namespace
    ];
    $file = fopen("$dir/composer.json", 'w+');
    fputs($file, str_replace('\/', '/', json_encode($json, JSON_PRETTY_PRINT)) );
    fclose($file);
}
