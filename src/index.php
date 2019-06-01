<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

error_log( "index.php :ENTREE" );

/* Initialisation TWIG, connexion à la BDD */
require('system/init.php');


/* ----------------------- */
/* Analyse des données GET */
/* ----------------------- */


if(isset($_GET['p']) AND $_GET['p']=="pageinfo"){                   /* Afficher les infos sur PHP */
    phpinfo();
}
elseif(isset($_GET['p']) AND $_GET['p']=="pagelogin"){              /* Page de connexion */
    $msg='' ; // message login                                      /* On appelle directement le modele */
    include('model/login.php');
    echo $twig->render('login.html',
    array('title' => 'Page de login',
        'assets_front' => 'assets/front/',
        'msg' => $msg));
}
elseif(isset($_GET['p']) AND $_GET['p']=="inscription"){            /* Inscription sur le site */
    include('controler/create.php');
    /*echo $twig->render('create.html',
        array('title' => 'Page de creation utilisateur',
            'assets_front' => 'assets/front/',
            'message' => "Et on s'inscrit !!!")); */
}
elseif(isset($_GET['p']) AND $_GET['p']=="deconnexion"){            /* Deconnexion utilisateur */
    include('controler/deconnexion.php');
    /*echo $twig->render('create.html',
        array('title' => 'Page de creation utilisateur',
            'assets_front' => 'assets/front/',
            'message' => "Et on s'inscrit !!!")); */
}
elseif(isset($_GET['p']) AND $_GET['p']=="dashboardC") {            /* Acces au Dashboard Client */
    include('controler/dashboardclient.php');
}
elseif(isset($_GET['p']) AND $_GET['p']=="dashboardF") {            /* Acces au Dashboard Freelance */
    include('controler/dashboardfreelance.php');
}
elseif(isset($_GET['p']) AND $_GET['p']=="gestionprojetC") {        /* Acces a la getion projet par le client, appelé du dashboard client */
    include('controler/gestionprojetclient.php');
}
elseif(isset($_GET['p']) AND $_GET['p']=="gestionprofil") {         /* Modifier le profil utilisateur hors password et mail (id de connexion) */
    include('controler/gestionprofil.php');
}
else{
    error_log("index.php clause else atteinte");            /* Gestion des autre cas */
    //include('model/accueil.php');
    session_start();
    if (isset($_SESSION['name'])) {                                  /* Gestion pour utilisateur connecté, test nécessite de faire un session_start()... */
        $_GET['p'] = "home";
        include('controler/home.php');

    } else {
        include('model/accueil.php');                                 /* Gestion pour utilisateur non connecté, page par défaut en mode non connecté */
        echo $twig->render('accueil.html',
            array('title' => 'Accueil Non Connecté',
                'assets_front' => 'assets/front/',
                'list' =>$listskill,
            ));
    }


    //error_log("index.php clause else atteinte");
    //session_start();
    //$_GET['p'] = "home";
    //include('controler/home.php');
}

//
error_log( "index.php : FIN" );

?>
