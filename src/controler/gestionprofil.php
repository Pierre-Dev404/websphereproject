<?php


error_log("controler gestionprofil.php : ENTREE");
session_start();
// Appel du modele
include('model/gestionprofil.php');


echo $twig->render('gestionprofil.html',
    array('title' => 'Gérer mon profil',
        'name' => $_SESSION['surname']." ".$_SESSION['name'],
        'updt_id_user' => $usrid,
        'menu_c_or_f' => $menuclientorfreelance,
        'msg_pop_up' => $message_pop_up,
        'updt_name' => $attribUser['name'],
        'updt_firstname' => $attribUser['firstname'],
        'updt_enterprise_name' => $attribUser['enterprise_name'],
        'updt_list_type' => $updt_list_type ,
        'updt_siret' => $attribUser['siret'],
        'updt_city' => $attribUser['city'],
        'updt_iban' => $attribUser['iban'],
        'updt_phone' => $attribUser['phone'],
        'deconnexion' => $_url_deconnexion )
);

?>