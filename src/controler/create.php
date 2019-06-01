<?php
error_log("controler create.php : Entree script");
// HTML
error_log("controler create.php : appel modele");
include('model/create.php');
error_log("controler create.php : sortie modele et appel rendu twig");
// include('view/head_table.php');          
// include('view/topbar.php');    
// include('view/sidebar.php');             
// include('view/project.php');
// include('view/footer.php');        
// include('view/footer_table.php');
//error_log("controler create.php : valeur recupere du modele  listeDesTypesUtilisateur $listeDesTypesUtilisateur");
echo $twig->render('create.html', 
array('title' => 'Inscription',
    'list' => $rowtype,
    'msg' => $msgcreate

//'array_types_uti' => $listeDesTypesUtilisateur

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
