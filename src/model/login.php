<?php

$user = new User ($bdd);

$mailTmp="";
$msg = "";

if(!empty($_POST)){
    $login = $user->connexion($_POST['mail'],$_POST['pass']);
}

?>
