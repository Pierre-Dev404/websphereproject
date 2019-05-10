<?php
$pageTitle="Listecomments";
// HTML 
include('model/comment.php');
// include('view/head_table.php');          
// include('view/topbar.php');    
// include('view/sidebar.php');             
// include('view/comment.php');
// include('view/footer.php');        
// include('view/footer_table.php');  
echo $twig->render('tablecomment.html',
array('title' => 'Liste des commentaires',
'tableTitle' => 'des commentaires',
'assets' => 'assets/',
'name' => $_SESSION['surname']." ".$_SESSION['name'],
'projectName' => "LaPiscine",
'projectNameShort' => "L-P",
'trTable' => $trTable,
'list' => $row,
'deconnexion' => $_url_deconnexion )
);