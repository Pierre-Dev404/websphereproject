<?php
// HTML     
include('model/create.php');
// include('view/head_table.php');          
// include('view/topbar.php');    
// include('view/sidebar.php');             
// include('view/article.php');
// include('view/footer.php');        
// include('view/footer_table.php');  
echo $twig->render('create.html', 
array('title' => 'Inscription', 
/*'assets' => 'assets/',
'name' => $_SESSION['surname']." ".$_SESSION['name'],
'projectName' => "LaPiscine",
'projectNameShort' => "L-P",
'mail' => $mailTmp,
'message' => $msg,
'nom' => $nomTmp,
'prenom' => $prenomTmp, */
)

);
