<?php
    // Appel du modele
    include('model/home.php');

error_log("controler home.php ENTREE");
    echo $twig->render('home.html',
    array('title' => 'Accueil connecté',
    'titre_compt' => $titre_comp,
    'name' => $_SESSION['surname']." ".$_SESSION['name'],
    'menuclientorfreelance' => $menuclientorfreelance,
    'buttonF' => $buttonrechercheF,
    'buttonC' => $buttonrechercheC,
    'listcomp' => $listcomp,
    'deconnexion' => $_url_deconnexion )
    );

?>