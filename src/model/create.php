<?php
error_log("model create.php : Entree script");

$msgcreate="";

    /*
     *  Il n'y a pas de donnes POST, on prepare les champs du formulaire
     */
    $type = new TypeUser ($bdd);
    $result = $type->getAllTypes();
    $rowtype="";

    // On récupère tout les types d'utilisateur afin de les passer en argumenter à la fonction createUser
    // si non selectionné, l'utilisateur est client.
    foreach($result as $element){
        $rowtype .= '
            <input type="checkbox" name="user_type[]" value='.$element['id_type'].' />'.$element['name'].'<br>
            ';
        }
if(!empty($_POST)){
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
            // Voir pour passer le user type en parametre plutot que recuperer les donnets dans objet USER
            $user = new User ($bdd);
            $retourCreate = $user->createUser($cr_name, $cr_firstname, $cr_mail, $cr_password, $cr_enterprise_name, $cr_siret, $cr_city, $cr_iban, $cr_phone);
            // tester ici s'il y a eu une duplicate entry sur le mail
            if ($retourCreate == 'DUPLICATE_REC') {
                $msgcreate="Mail déjà existant";
                // si le mail est dejà existant en bdd, on return depuis la classe user : 'DUPLICATE_REC'
                // On vérifie ici, si le message retourné est égal à duplicate_rec, si oui on passe le message
                // mail deja existant

            } else {
                // sinon on se connecte
                $login = $user->connexion($cr_mail, $cr_password);
                $msg = $login;
            }
        } else {
            // Sinon cela veut dire que tous les champs ne sont pas rempli
            $msg = "Tous les champs ne sont pas remplis";
        }
    }

 


?>

