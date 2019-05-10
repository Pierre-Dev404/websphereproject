<?php
$pageTitle="Liste articles";
// HTML 
include('model/article.php');         
echo $twig->render('table.html',

array('title' => 'Liste des utilisateurs', 
'assets' => 'assets/',
'name' => $_SESSION['surname']." ".$_SESSION['name'],
'projectName' => "LaPiscine",
'projectNameShort' => "L-P",
'titre_table'=>"des articles",
'list' => $row )
);

var_dump($row);