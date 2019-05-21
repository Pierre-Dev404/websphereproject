<?php
$pageTitle="Liste des projets";
// HTML 
include('model/project.php');
echo $twig->render('allproject.html',

array('title' => 'Liste des projets',
'assets' => 'assets/',
'name' => $_SESSION['surname']." ".$_SESSION['name'],
'projet' => 'Liste des projets',
'list' => $row )
);