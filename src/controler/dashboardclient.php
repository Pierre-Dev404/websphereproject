<?php
session_start();

include('model/dashboardclient.php');


echo $twig->render('dashboardClient.html',
    array('title' => 'Dashboard client',
        'menuclientorfreelance' => $menuclientorfreelance,
        'name' => $_SESSION['surname']." ".$_SESSION['name'],
       'mesprojets' => $mesprojets,
        'mesprojetstermines'=> $mesprojetstermines,
       'formprojet' => $formulaire_creation_projet
        ));