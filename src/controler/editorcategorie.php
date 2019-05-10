<?php
$pageTitle="Editer les catégories d'articles";
// CODE PHP 
if(isset($_GET['id'])){
    $idCategorie=$_GET['id'];
    include('model/modifcategorie.php');        
}
else{
    include('model/editorcategorie.php');  
    $title="";
    $content="";
    $coverImage="";
}
        
echo $twig->render('editorcategorie.html', 
array('title' => $pageTitle, 
'assets' => 'assets/',
'name' => $_SESSION['surname']." ".$_SESSION['name'],
'projectName' => "LaPiscine",
'projectNameShort' => "L-P",
'titleCategorie' => $title,
'contentArticle' => $content,
'coverImageArticle' => $coverImage)
); 