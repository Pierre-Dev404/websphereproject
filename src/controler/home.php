<?php
    
    $pageTitle="Accueil";

    // Appel du modele
    include('model/home.php');


    echo $twig->render('home.html',
    array('title' => 'Accueil', 
    'assets' => 'assets/',
    'name' => $_SESSION['surname']." ".$_SESSION['name'],
    'menuclientorfreelance' => $menuclientorfreelance,
    'buttonF' => $buttonrechercheF,
    'buttonC' => $buttonrechercheC,
    'listcomp' => $listcomp,
    'deconnexion' => $_url_deconnexion )
    );

?>