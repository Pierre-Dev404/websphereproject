<?php
error_log("controler create.php : Entree script");

error_log("controler create.php : appel modele");
include('model/create.php');
error_log("controler create.php : sortie modele et appel rendu twig");

echo $twig->render('create.html', 
array('title' => 'Inscription',
    'list' => $rowtype,
    'msg' => $msgcreate
)

);
