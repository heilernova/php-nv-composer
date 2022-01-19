<?php
namespace PhpNv\Template;

function generate_www(string $dir, $path_app){
//     RewriteEngine On
// RewriteRule ^(.*) index.php?url=$1 [L,QSA]

    if (!file_exists("$dir/www")) mkdir("$dir/www");

    $htacess = fopen("$dir/www/.htaccess", 'w');
    fputs($htacess, "RewriteEngine On\n");
    fputs($htacess, "RewriteRule ^(.*) index.php?url=$1 [L,QSA]");

    $index = fopen("$dir/www/index.php", 'w');
    fputs($index, "<?php\n");
    fputs($index, "require '$path_app';");
}