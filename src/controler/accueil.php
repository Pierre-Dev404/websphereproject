<?php
error_log( "controler accueil.php :ENTREE" );
include('model/accueil.php');

echo $twig->render('accueil.html',
        array(
        )
// Le include avec les parametres twig sont dans index.php : a modifier;
    );

error_log( "controler accueil.php : FIN" );