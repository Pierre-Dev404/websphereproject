<?php
error_log( "login.php :ENTREE" );
$user = new User ($bdd);

$mailTmp="";
$msg = "";
$login='';
if(!empty($_POST)){
    $login = $user->connexion($_POST['mail'],$_POST['pass']);
}
$msg=$login;
error_log( "login.php : SORTIE" );
?>
