<?php
    
    $pageTitle="Accueil";
    include('model/home.php');

    echo $twig->render('home.html',
    array('title' => 'Accueil', 
    'assets' => 'assets/',
    'name' => $_SESSION['surname']." ".$_SESSION['name'],
    'projectName' => "LaPiscine",
    'projectNameShort' => "L-P",
    'formulaire_client' => $formulaire_client,
    'formulaire_freelance' => $formulaire_freelance,
    'deconnexion' => $_url_deconnexion )
    );

?>