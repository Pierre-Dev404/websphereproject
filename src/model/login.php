<?php

/* ----------------------------------------------------------------------*/
/*      Appel de la fonction "connexion" de la classe User               */
/*      Une redirection (header location) sera faite vers                */
/*      la page admin.php                                                */
/*  -------------------------------------------------------------------- */
error_log( "login.php :ENTREE" );
$user = new User ($bdd);

$msg = "";
$login='';
if(!empty($_POST)){
    $login = $user->connexion($_POST['mail'],$_POST['pass']);
}
$msg=$login;
error_log( "login.php : SORTIE" );
?>
