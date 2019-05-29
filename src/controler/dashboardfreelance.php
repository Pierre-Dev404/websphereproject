<?php
error_log("controler dashboardfreelance.php : ENTREE CONTROLER");
session_start();
error_log("controler dashboardfreelance.php : APPEL MODELE");
include('model/dashboardfreelance.php');
error_log("controler dashboardfreelance.php : SORTIE MODELE");

error_log("controler dashboardfreelance.php : APPEL TWIG");
echo $twig->render('dashboardFreelance.html',
    array('title' => 'Dashboard Freelance',
        'comp_freelance' => $form_insert_skill,
        'projet_propose' => $mesprojetsproposes,
        //'contact_info' => $getcontactinfo,
        'projet_accept' => $mesprojetsacceptes,
        'projet_a_valider' => $mesprojetsavalider,
        'projet_termine' => $mesprojetstermine,
        'menu_c_or_f' => $menuclientorfreelance,
        'name' => $_SESSION['surname']." ".$_SESSION['name'],
    ));
error_log("controler dashboardfreelance.php : SORTIE CONTROLER");