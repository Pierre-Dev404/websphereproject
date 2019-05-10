<?php

/* V1: Trop simple et manuel */
// include 'src/class/Categorie.php';
// include 'src/class/User.php';

/* V2 */
$directory="src/class/";
$listClass = scandir($directory);
foreach($listClass as $class){
    if(strstr($class, '.php', true)){
        require $directory.$class;
    }
}

/* V3 : Pb car déjà utilisé par composer */
// spl_autoload_register(function ($class) {
//     include "../class/".$class . ".php";
// });