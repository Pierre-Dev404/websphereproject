<?php

session_start();
include('model/dashboardfreelance.php');


echo $twig->render('dashboardFreelance.html',
    array('title' => 'Dashboard Freelance',
        'comp_freelance' => $form_insert_skill,
        'projet_propose' => $mesprojetsproposes,
        'name' => $_SESSION['surname']." ".$_SESSION['name'],
    ));