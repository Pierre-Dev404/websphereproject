<?php
/*if(session_status() !== PHP_SESSION_ACTIVE) {
    error_log("admin.php : Session non active") ;
  } else {
    error_log("admin.php : Session deja active") ;
  }
$SSID=$_SESSION['id'];
error_log( "admin.php : session ID $SSID" );
*/
session_start();

$SSID=$_SESSION['id'];
error_log( "admin.php :ENTREE" );
error_log( "admin.php : Apres session start session ID $SSID" );

/*
if(session_status() !== PHP_SESSION_ACTIVE) {
  error_log("admin.php : Apres session start Session non active") ;
} else {
  error_log("admin.php : Apres session start Session deja active") ;
}
*/
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  require('system/init.php');

  // /index.php != de index.php
  $_url_deconnexion = "admin.php?p=deconnexion";

  if (!empty($_SESSION)) {
    /*$projectName = "LaPiscine";
    $projectNameShort = "LP"; */
    # variable name utilisee dans home
    $name = $_SESSION['surname']." ".$_SESSION['name'];
    error_log("admin.php : Session Name $name") ;
    $ATTRIBUTS="";
    if (isset($_SESSION['Freelance'])) {
      error_log( "admin.php : Utilisateur est Freelance" );
      $ATTRIBUTS="Freelance";
    }
    if  (isset($_SESSION['Client'])) {
      error_log( "admin.php : Utilisateur est Client" );
      $ATTRIBUTS=$ATTRIBUTS."Client";
    }
    //isset = est-ce que la variable est définie ?
    // Si non positionnée on se redirige vers une page d'accueil
    if (!isset($_GET['p'])) {
      $_GET['p'] = "home";
      $PAGE = $_GET['p'] ;
    } else {
      $getArgs=$_GET['p'];
      error_log( "admin.php : arguments get $getArgs" );
    }
    if (file_exists('controler/'.$_GET['p'].'.php')) {
      error_log("admin.php : Entree dans la page $PAGE, avec le session ID $SSID et attribut $ATTRIBUTS ");
      include('controler/'.$_GET['p'].'.php');
      $SSID=$_SESSION['id'];


    } else {
      echo "<p>admin.php : cette page n'existe pas</p>";
    }
  } else {
    // Traitement du formulaire de connexion
    // On créé les sessions ici lorsque le formulaire a été envoyé
    include('model/login.php');
    // HTML
    //include('view/login.php');
    echo $twig->render('login.html',
    array('title' => 'Login',
    'assets' => 'assets/',
    'mailTmp' => $mailTmp,
    'message' => $msg));
  }

error_log( "admin.php : FIN" );
?>
