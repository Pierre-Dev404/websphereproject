<?php
$pageTitle="Categoriearticles";
// HTML 
include('model/categoriearticles.php');         
// include('view/head_table.php');          
// include('view/topbar.php');    
// include('view/sidebar.php');             
// include('view/article.php');
// include('view/footer.php');        
// include('view/footer_table.php');  
echo $twig->render('categoriearticles.html', 
array('title' => 'Catégories des articles', 
'assets' => 'assets/',
'name' => $_SESSION['surname']." ".$_SESSION['name'],
'projectName' => "LaPiscine",
'projectNameShort' => "L-P",
'list' => $row )
);