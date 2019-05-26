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
    array('nm_id_project' => $_SESSION['$nm_id_project'],
        'nm_title' => $_SESSION['nm_title'],
        'nm_start_date' => $_SESSION['nm_start_date'],
        'nm_end_date' => $_SESSION['nm_end_date'],
        'nm_price' => $_SESSION['nm_price'],
        'nm_content' => $_SESSION['nm_content'],
        'nm_status_name' => $_SESSION['nm_status_name'],

        'list' => $listskill,
        /*
        'userbyskill' => $nm_userbyskill ,
        'userbyskill' => $_SESSION['nm_userbyskill'],
        */
        'userbyskill' => $_SESSION['nm_userbyskill'],
        'nm_idProjectStatus' => $_SESSION['nm_idProjectStatus'],
        'name' => $_SESSION['surname']." ".$_SESSION['name'],
    ));
