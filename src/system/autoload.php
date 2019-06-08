<?php

/* V1: Trop simple et manuel */
// include 'src/class/TypeUserser.php/ include 'src/class/User.php';

/* V2 */


$directory="class/";
$listClass = scandir(getcwd()."/".$directory);
foreach($listClass as $class){
    if(strstr($class, '.php', true)){
        require $directory.$class;
    }
}

/* V3 : Pb car déjà utilisé par composer */
// spl_autoload_register(function ($class) {
//     include "../class/".$class . ".php";
// });