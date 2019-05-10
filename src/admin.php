<?php
session_start();
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  require('system/init.php');

  // /index.php != de index.php
  $_url_deconnexion = "admin.php?p=deconnexion";

  if (!empty($_SESSION)) {
    $projectName = "LaPiscine";
    $projectNameShort = "LP";
    $name = $_SESSION['surname']." ".$_SESSION['name'];

    //isset = est-ce que la variable est définie ?
    if (!isset($_GET['p'])) {
      $_GET['p'] = "home";
      $GEGE = $_GET['p'] ;
    }
    if (file_exists('controler/'.$_GET['p'].'.php')) {
      include('controler/'.$_GET['p'].'.php');
      echo "<pre> ON EST DANS $GEGE</pre>" ;
    } else {
      echo "cette page n'existe pas";
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

?>
