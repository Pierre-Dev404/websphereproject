<?php

session_start();
include('model/dashboardfreelance.php');


echo $twig->render('dashboardFreelance.html',
    array('title' => 'Dashboard Freelance',
        'comp_freelance' => $formulaire_freelance,
        'name' => $_SESSION['surname']." ".$_SESSION['name'],
    ));