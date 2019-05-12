<?php
$pageTitle="Liste des projets";
// HTML 
include('model/project.php');
echo $twig->render('home.html',

array('title' => 'Liste des utilisateurs', 
'assets' => 'assets/',
'projet' => 'Liste des projets',
'list' => $row )
);