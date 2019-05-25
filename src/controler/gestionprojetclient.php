<?php
error_log("controler/gestionprojetclient.php : Entree script");
/**
 * Created by PhpStorm.
 * User: pierremeunier
 * Date: 2019-05-24
 * Time: 15:41
 */

session_start();
include('model/gestionprojetclient.php');

echo $twig->render('gestionprojetclient.html',
    array('nm_id_project' => $nm_id_project,
        'nm_title' => $nm_title,
        'nm_start_date' => $nm_start_date,
        'nm_end_date' => $nm_end_date,
        'nm_price' => $nm_price,
        'nm_content' => $nm_content,
        'nm_status_name' => $nm_status_name,
        'nm_menu_contextuel_projet' => $nm_menu_contextuel_projet,
        'nm_idProjectStatus' => $nm_id_project_status,
        'name' => $_SESSION['surname']." ".$_SESSION['name'],
    ));
