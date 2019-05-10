<?php

$user = new User ($bdd);

$mailTmp="";
$msg = "";

if(!empty($_POST)){
    $login = $user->connexion($_POST['mail'],$_POST['pass']);
    $mailTmp = $user->_mail;
    $msg = $login;
    echo "<pre>SORTIE CONNEXION</pre>";
    echo "<pre> $msg </pre>";
}

?>
