<?php
// On met dans le fichier init.php tous les appels de fonctions, 
// chargements de librairies et instanciations des classes 
// communs à toutes les pages
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=websphere;charset=utf8', 'root', 'root');
    #GG
    #$bdd = new PDO('mysql:host=localhost;port=8889;dbname=blog_e_commerce;charset=utf8', 'root', 'root');
    # $GLOBALS['DB']=$bdd;
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

/* On charge toutes les classes PHP */
require_once 'system/autoload.php';

// On charge toutes les librairies installées avec composer 
// (dont twig)
require_once 'vendor/autoload.php';

// $loader est une variable qui contient un objet ayant été généré
// grâce à l'instanciation de la classe Twig_Loader_Filesystem
// à laquelle nous avons passé un paramètre qui est 'templates'
// en l'occurence il s'agit d'un chemin d'accès (PATH)
$loader = new Twig_Loader_Filesystem('templates');

// $twig est une variable qui contient un objet ayant été généré
// grâce à l'instanciation de la classe Twig_Environment
// à laquelle nous avons passé un paramètre qui est la variable $loader
// en l'occurence il s'agit d'un objet
$twig = new Twig_Environment($loader);
$_url_deconnexion = "admin.php?p=deconnexion";
$twig->addGlobal('deconnexion' , $_url_deconnexion);
