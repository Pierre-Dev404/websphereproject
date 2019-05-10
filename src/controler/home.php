<?php
    
    $pageTitle="Accueil";

    echo $twig->render('dashboard.html', 
    array('title' => 'Accueil', 
    'assets' => 'assets/',
    'name' => $_SESSION['surname']." ".$_SESSION['name'],
    'projectName' => "LaPiscine",
    'projectNameShort' => "L-P",
    'deconnexion' => $_url_deconnexion )
    );

?>