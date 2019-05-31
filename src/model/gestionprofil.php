<?php
/**
 * Created by PhpStorm.
 * User: pierremeunier
 * Date: 2019-05-30
 * Time: 16:02
 */
error_log("model gestionprofil.php : ENTREE");


if (isset($_SESSION['Client'])) {
    $menuclientorfreelance="";
    error_log("model gestionprofil.php : On est Client");
    $menuclientorfreelance .= '

        <li><a href="/websphereProject/src/?p=dashboardC">Dashboard client</a></li>
    
       ';
}


if (isset($_SESSION['Freelance'])) {

    error_log("model gestionprofil.php : on est Freelance");
    $menuclientorfreelance="";
    $menuclientorfreelance .= '

        <li><a href="/websphereProject/src/?p=dashboardF">Dashboard freelance</a></li>
        ';

}


// On instancie un objet de la classe User pour pouvoir appeler la méthode updateUser
$gestuser = new User ($bdd);
//var_dump($_SESSION);
$usrid=$_SESSION['id'];

// On recherche les informations pour pré-remplir le formulaire dans gestionprofil.html
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
    //
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
        $updt_user_type=$_POST['profile_check']['0']; // Pas de foereach un seul element, on recup le premier
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


