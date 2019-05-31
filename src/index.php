<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

error_log( "index.php :ENTREE" );

/* Exemple de pagination pour l'url rewriting */
require('system/init.php');

if(isset($_GET['p']) AND $_GET['p']=="pageinfo"){
    phpinfo();
}
elseif(isset($_GET['p']) AND $_GET['p']=="pagelogin"){
    $msg='' ; // message login
    include('model/login.php');
    echo $twig->render('login.html',
    array('title' => 'Page de login',
        'assets_front' => 'assets/front/',
        'msg' => $msg));
}
elseif(isset($_GET['p']) AND $_GET['p']=="inscription"){
    include('controler/create.php');
    /*echo $twig->render('create.html',
        array('title' => 'Page de creation utilisateur',
            'assets_front' => 'assets/front/',
            'message' => "Et on s'inscrit !!!")); */
}
elseif(isset($_GET['p']) AND $_GET['p']=="deconnexion"){
    include('controler/deconnexion.php');
    /*echo $twig->render('create.html',
        array('title' => 'Page de creation utilisateur',
            'assets_front' => 'assets/front/',
            'message' => "Et on s'inscrit !!!")); */
}
elseif(isset($_GET['p']) AND $_GET['p']=="dashboardC") {
    include('controler/dashboardclient.php');
}
elseif(isset($_GET['p']) AND $_GET['p']=="dashboardF") {
    include('controler/dashboardfreelance.php');
}
elseif(isset($_GET['p']) AND $_GET['p']=="gestionprojetC") {
    include('controler/gestionprojetclient.php');
}
elseif(isset($_GET['p']) AND $_GET['p']=="gestionprofil") {
    include('controler/gestionprofil.php');
}
else{
    error_log("index.php clause else atteinte");
    //include('model/accueil.php');
    session_start();
    if (isset($_SESSION['name'])) {
        $_GET['p'] = "home";
        include('controler/home.php');

    } else {
        include('model/accueil.php');
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
error_log( "index.php :FIN" );

?>
