<?php
error_log("model create.php : Entree script");
/*
$mailTmp="";
$nomTmp="";
$prenomTmp="";
$msg="";
*/


if(empty($_POST)){
    /*
     *  Il n'y a pas de donnes POST, on prepare les champs du formulaire (checkbox crees dynamiquement
     */
    $type = new TypeUser ($bdd);
    $result = $type->getAllTypes();
    $rowtype="";
    foreach($result as $element){
        $rowtype .= '
            <input type="checkbox" name="user_type[]" value='.$element['id_type'].' />'.$element['name'].'<br>
            ';
        }
    }
    else {
        /*
        *  Il a des donnes POST envoyees par le formulaire precedemment affiche
         * On recupere les champs du formulaire pour creer l'objet USER
         * et appeler la methode createUser qui renverra par une fonction header
         * sur la page admin
        */
        if (!empty($_POST['nom'])
            AND !empty($_POST['prenom'])
            AND !empty($_POST['mail'])
            AND !empty($_POST['password'])
            AND !empty($_POST['enterprise_name'])
            AND !empty($_POST['siret'])
            AND !empty($_POST['city'])
            AND !empty($_POST['phone'])
            AND !empty($_POST['iban'])) {
            $cr_name = $_POST['nom'];
            $cr_firstname = $_POST['prenom'];
            $cr_mail = $_POST['mail'];
            $cr_password = $_POST['password'];
            $cr_enterprise_name = $_POST['enterprise_name'];
            $cr_siret = $_POST['siret'];
            $cr_city = $_POST['city'];
            $cr_phone = $_POST['phone'];
            $cr_iban = $_POST['iban'];

            error_log("model create.php : instanciation objet User ");
            $user = new User ($bdd);

            $user->createUser($cr_name, $cr_firstname, $cr_mail, $cr_password, $cr_enterprise_name, $cr_siret, $cr_city, $cr_iban, $cr_phone);
            $login = $user->connexion($cr_mail, $cr_password);
            $mailTmp = $user->_mail;
            $msg = $login;
            error_log("model create.php : SORTIE CONNEXION APRES CREATION USER");
            error_log("model create.php : msg est $msg");
            //$msg = "Tous les champs ne sont pas remplis";
        } else {
            $msg = "Tous les champs ne sont pas remplis";
        }
        //penser à vérifier la présence du cnfirm password

        // ensuite ajouter vérif password et password confirm identiques sinon on affiche un message

    }

            
   // print_r($_POST);
       

// else{
//     $message=""
//     $mailtemp = $_POST['mail'];
//     $nomTemp = $_POST['nom'];
//     $prenomTemp = $_POST['prenom'];
// }




        // if(!empty($_POST)){
        //     $req = $bdd->prepare("INSERT INTO user (nom, prenom, mail, password) VALUES($nom, $prenom, $email, $password)");
        //     $req->bindParam(':mail', $mail);
        //     $req->bindParam(':password', $password);
        //     $req->execute();
        // }
    


// On récupère les $_POST et on en fait des variables
 
 


?>

