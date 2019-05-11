<?php
$pageTitle="Liste des projets";
// HTML 
include('model/article.php');         
echo $twig->render('table.html',

array('title' => 'Liste des utilisateurs', 
'assets' => 'assets/',
'projet' => 'Liste des projets',
'list' => $row )
);