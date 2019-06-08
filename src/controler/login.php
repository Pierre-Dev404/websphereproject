<?php
/**
 * Created by PhpStorm.
 * User: pierremeunier
 * Date: 2019-06-06
 * Time: 19:22
 */


include('model/login.php');
echo $twig->render('login.html',
    array('title' => 'Page de login',
        'assets_front' => 'assets/front/',
        'msg' => $msg));