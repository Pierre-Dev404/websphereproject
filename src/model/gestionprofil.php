<?php
/**
 * Created by PhpStorm.
 * User: pierremeunier
 * Date: 2019-05-30
 * Time: 16:02
 */


$message_pop_up='<div id="pop-up" class="modal">
            <h3>Client :</h3>
            <p> Vous êtes client et souhaitez faire réaliser un projet web ?<br>
                Quelques étapes à suivre : <br>
                1- Allez sur votre dashboard client, créez un projet en remplissant les champs indiqués et postez le.<br>
                2- Après création, cliquez sur \"gérer votre projet\" depuis cette page, vous pourrez choisir un ou plusieurs freelances selon <br>
                les compétences souhaitées à la réalisation. <br>
                3- Attendez qu\'un freelance accepte votre projet pour pouvoir vous mettre en relation.
            </p>

            <h3>Freelance :</h3>
            <p> Si vous êtes freelance, consultez votre dashboard régulièrement pour voir si vous avez des projets proposés <br>
                afin de les accepter.
                Le cas échéant, vous pourrez accéder aux coordonnées de votre interlocuteur.
            </p>
            <a href="#" rel="modal:close">Close</a>
        </div>';


$menuclientorfreelance="";
$menuclientorfreelance="";
error_log("model gestionprofil.php : ENTREE");


if (isset($_SESSION['Client'])) {
    error_log("model gestionprofil.php : On est Client");
    $menuclientorfreelance = '

        <a href="/websphereProject/src/?p=dashboardC">Dashboard client</a>
    
       ';
}


if (isset($_SESSION['Freelance'])) {

    error_log("model gestionprofil.php : on est Freelance");

    $menuclientorfreelance .= '

        <a href="/websphereProject/src/?p=dashboardF">Dashboard freelance</a>
        ';

}


//  instancie un objet de la classe User pour pouvoir appeler la méthode  et getuserinfo
$gestuser = new User ($bdd);
//var_dump($_SESSION);
$usrid=$_SESSION['id'];

// Récupération des  informations pour pré-remplir le formulaire dans gestionprofil.html
$attribUser=$gestuser->getUserInfo($usrid);
// Gestion du menu ajout status
// on considère que le cas interdit ni Client ni Freelance est géré  (createUser)

// Valeur par defaut on a rien a ajouter
$updt_list_type="<input class='user' type='checkbox' name='profile_check[]' value='noupdate' style='visibility:hidden'/> <br>";

if (!isset($_SESSION['Client'])) {
    // On donne la possibilité d'ajouter un profil client
    $updt_list_type="Ajouter profil client <input class='user' type='checkbox' name='profile_check[]' value='2' /> <br>";
}
if (!isset($_SESSION['Freelance'])) {
    // On donne la possibilité d'ajouter un profil Freelance
    $updt_list_type="Ajouter profil Freelance <input class='user' type='checkbox' name='profile_check[]' value='1' /> <br>";
}

if (!empty($_POST)) {
    // données POST :
    // on a des données post venant du formulaire
    // il n'y a qu'un formulaire pas besoin de tester l'origine des donnees POST
    error_log("model gestionprofil.php : on a des données POST");

    $updt_name=$_POST['name'];
    $updt_firstname=$_POST['firstname'];
    $updt_enterprise_name=$_POST['enterprise_name'];
    $updt_siret=$_POST['siret'];
    $updt_city=$_POST['city'];
    $updt_iban=$_POST['iban'];
    $updt_phone=$_POST['phone'];

    if (isset($_POST['profile_check'])) {
        error_log("model gestionprofil.php : on a recu donnees case a cocher");
        $updt_user_type=$_POST['profile_check']['0'];
    } else {
        error_log("model gestionprofil.php : on a recu aucune donnees case a cocher, pas de modif ");
        $updt_user_type='noupdate'; // Pas de modif ..
    }
    error_log("model gestionprofil.php : Valeur checkbox");
    error_log(print_r($updt_user_type));




    error_log("model gestionprofil.php, appel  updateUser $usrid, $updt_name, $updt_firstname, $updt_enterprise_name, $updt_siret, $updt_city, $updt_iban, $updt_phone,$updt_user_type");


    // Renvoi sur le controler dans la methode updateUser
    // Les données du formulaire sont rechargées avec les nouvelles valeurs


    $update_user_info=$gestuser->updateUser($usrid, $updt_name, $updt_firstname, $updt_enterprise_name, $updt_siret, $updt_city, $updt_iban, $updt_phone,$updt_user_type);



}


