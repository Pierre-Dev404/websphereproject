<?php
$pageTitle="Liste des utilisateurs";
// HTML 
include('model/user.php');         
echo $twig->render('table.html', 
array('title' => 'Liste des utilisateurs', 
'assets' => 'assets/',
'name' => $_SESSION['surname']." ".$_SESSION['name'],
'projectName' => "LaPiscine",
'projectNameShort' => "L-P",
'list' => $row,
'deconnexion' => $_url_deconnexion )
);