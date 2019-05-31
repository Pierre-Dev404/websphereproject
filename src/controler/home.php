<?php
    
    $pageTitle="Accueil";

    // Appel du modele
    include('model/home.php');


    echo $twig->render('home.html',
    array('title' => 'Accueil connecté',
    'assets' => 'assets/',
       'titre_compt' => $titre_comp,
    'name' => $_SESSION['surname']." ".$_SESSION['name'],
    'menuclientorfreelance' => $menuclientorfreelance,
    'buttonF' => $buttonrechercheF,
    'buttonC' => $buttonrechercheC,
    'listcomp' => $listcomp,
    'deconnexion' => $_url_deconnexion )
    );

?>