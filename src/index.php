<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

/* Exemple de pagination pour l'url rewriting */
require('system/init.php');

if(isset($_GET['p']) AND $_GET['p']=="pageinfo"){
    phpinfo();
}
elseif(isset($_GET['p']) AND $_GET['p']=="pagelogin"){
    include('model/login.php');
    echo $twig->render('login.html',
    array('title' => 'Page de login',
        'assets_front' => 'assets/front/',
        'message' => "Coucou les amis"));
}
elseif(isset($_GET['p']) AND $_GET['p']=="inscription"){
    include('controler/create.php');
    /*echo $twig->render('create.html',
        array('title' => 'Page de creation utilisateur',
            'assets_front' => 'assets/front/',
            'message' => "Et on s'inscrit !!!")); */
}
elseif(isset($_GET['p']) AND $_GET['p']=="project"){
    include('controler/project.php');
}
elseif(isset($_GET['p']) AND $_GET['p']=="deconnexion"){
    include('controler/deconnexion.php');
    /*echo $twig->render('create.html',
        array('title' => 'Page de creation utilisateur',
            'assets_front' => 'assets/front/',
            'message' => "Et on s'inscrit !!!")); */
}
#elseif(isset($_GET['p']) AND $_GET['p']=="argh"){
#   include('model/argh.php');
    #echo $twig->render('create.html',
     #   array('title' => 'Page de creation utilisateur',
      #      'assets_front' => 'assets/front/',
       #     'message' => "Et on s'inscrit !!!"));
#}
else{
    include('model/front.php');
    echo $twig->render('accueil.html',
    array('title' => 'Accueil',
    'assets_front' => 'assets/front/'
    ));
}


?>
