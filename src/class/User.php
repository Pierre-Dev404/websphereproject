<?php

class User
{
    private $_id;
    public $_name;
    public $_firstname;
    public $_mail;
    private $_password;
    private $_status;
    private $_civilite;
    private $_avatar;
    private $_verifyPass;
    private $_bdd;

    function __construct($bdd, $id = null)
    {
        $this->_bdd = $bdd;
        if($id != NULL){
            $user = $this->getUser($id);
            $this->_id = $id;
            $this->_name = $user['nom'];
            $this->_firstname = $user['prenom'];
            $this->_mail = $user['mail'];
            $this->_password = $user['password'];
            $this->_avatar = $user['avatar'];
            $this->_civilite = $user['id_civilite'];
            $this->_status = $user['id_user_status'];
        }
    }
    
    function getUser($id)
    {
        $req = $this->_bdd->prepare("SELECT nom, prenom, mail, password, avatar, id_civilite, id_user_status 
                                FROM user
                                WHERE id=:id");
                    $req->bindParam(':id', $id);
                    $req->execute();
                    $result = $req->fetch();
                    return $result;
    }

    function userBySkills()
    {
            foreach ($_POST['user_skill'] as $type) {
                $req = $this->_bdd->prepare("SELECT u.name, u.firstname, u.enterprise_name, u.city
                                    FROM users u 
                                    JOIN user_skill us on u.id_user = us.id_user
                                    JOIN skill s on s.id_skill = us.id_skill
                                    WHERE us.id_skill=:skill
                                      ");
                if ($req->execute(array(
                    'skill' => $type,
                ))) ;
                $result = $req->fetchAll();
                return $result;
        }
    }

    function getAllFreelance()
    {
        // Recupere les utilisateurs par ID type = 1 => Freelance

        $req = $this->_bdd->prepare("SELECT u.name, u.firstname, u.mail, u.enterprise_name
                                FROM users u 
                                JOIN user_type ut ON u.id_user = ut.id_user
                                WHERE ut.id_type = 1");
        $req->execute();
        $result = $req->fetchAll();
        return $result;
    }


    function getAllUser()
    {
        $req = $this->_bdd->prepare("SELECT id, nom, prenom, mail, avatar, id_civilite, id_user_status 
                                    FROM user");
                    $req->execute();
                    $result = $req->fetchAll();
                    return $result ;
    }

    function createUser($cu_name, $cu_firstname, $cu_mail, $cu_password, $cu_enterprise_name, $cu_siret, $cu_city, $cu_iban, $cu_phone)
    {


        $this->_password = password_hash($cu_password, PASSWORD_DEFAULT);
        $req = $this->_bdd->prepare("INSERT INTO users (name, firstname, mail, password, enterprise_name, siret, city, iban, phone)
                                    VALUES (:name, :firstname, :mail, :password, :enterprise_name, :siret, :city, :iban, :phone)");
        if ($req->execute(array(
            'name' => $cu_name,
            'firstname' => $cu_firstname,
            'mail' => $cu_mail,
            'password' => $this->_password,
            'enterprise_name' => $cu_enterprise_name,
            'siret' => $cu_siret,
            'city' => $cu_city,
            'iban' => $cu_iban,
            'phone' => $cu_phone
        ))) {
            echo "Succes\n";
            //$user_last_id = mysqli_insert_id();
            $user_last_id = $this->_bdd->lastInsertId();
            echo($user_last_id);
        } else {
            $erreur_insert=$req->errorInfo() ;
            if ($erreur_insert['0'] == '23000'){
               // $disp_error=$erreur_insert['2'];
                //echo "une erreur est survenue lors de l'insertion $disp_error";
                return "DUPLICATEMAIL";
            }

           print_r($req->errorInfo());
        };
        // Il y a au moins un etat actif, on ne peut pas etre ni client ni freelance, defaut= client
        if (empty($_POST['user_type'])) {
            // On affecte un array car on attend un *TABLEAU* de competences
            $_POST['user_type']=array(2);
        }
        #print_r($_POST['user_type']) ;
        /*foreach($_POST['user_type'] as $valeur)
            {
                echo "<pre>La checkbox $valeur a été cochée<br></pre>";
            }*/
        foreach ($_POST['user_type'] as $type) {
            error_log("La valeur de type traitee est $type ");
            $req = $this->_bdd->prepare("INSERT INTO user_type (id_type, id_user)
                                    VALUES (:id_type, :id_user)");
            if ($req->execute(array(
                'id_type' => $type,
                'id_user' => $user_last_id
            ))) {
                echo "Succes";
            } else {
                echo "une erreur est survenue lors de l'insertion dans usertype";
                print_r($req->errorInfo());
            }


            #return true;
        }
    }

        function updateUser($id, $name, $firstname, $mail, $avatar, $civilite, $status)
        {
            $req = $this->_bdd->prepare("UPDATE user
                                    SET nom=:nom, prenom=:prenom, mail=:mail, avatar=:avatar, 
                                        id_civilite=:idcivilite, id_user_status=:idstatus
                                    WHERE id=:id");
            $req->execute(array(
                'id' => $id,
                'nom' => $name,
                'prenom' => $firstname,
                'mail' => $mail,
                'avatar' => $avatar,
                'idcivilite' => $civilite,
                'idstatus' => $status
            ));
            return true;
        }

        function deleteUser($id)
        {
            $req = $this->_bdd->prepare("DELETE FROM user WHERE id=:id");
            $req->execute(array('id' => $id));
            return true;
        }

        function passChange($id, $oldPassword, $newPassword)
        {
            $req = $this->_bdd->prepare("SELECT password FROM user WHERE id=:id");
            $req->bindParam(':id', $id);
            $req->execute();
            $result = $req->fetch();
            $this->_verifyPass = $result['password'];
            if (password_verify($oldPassword, $this->_verifyPass)) {
                $this->_password = password_hash($newPassword, PASSWORD_DEFAULT);
                $req = $this->_bdd->prepare("UPDATE user
                                        SET password=:newPassword
                                        WHERE id=:id");
                $req->execute(array('id' => $id, 'newPassword' => $this->_password));
                return true;
            } else {
                return 'Le mot de passe ne correspond pas.';
            }
        }


        /*function getAllTypes()
        {
            error_log("User.php, methode getAllTypes : entree dans la fonction ");
            $listeDesTypesUtilisteur=$this->_bdd->query('SELECT id_type, name FROM type');
            foreach  ($listeDesTypesUtilisteur as $typeExistant) {
                $nameTypeExistant[]= array($typeExistant['id_type'],$typeExistant['name']);
                error_log("User.php, methode getAllTypes : Element ajoute");


            }

            return($nameTypeExistant) ;

        }
        */





        function connexion($mail, $password)
        {
            $req = $this->_bdd->prepare('SELECT id_user, name, firstname, mail, password, enterprise_name, siret, city, iban, phone FROM users WHERE mail= :mail');
            $req->bindParam(':mail', $mail);
            $req->execute();
            $checkMail = $req->fetch();
            error_log("User.php, methode connexion : Connexion init") ;
            if (!empty($checkMail)) {
                $checkPass = password_verify($password, $checkMail['password']);

                if ($checkPass) {
                    session_start();
                    #session_unset() ;
                    #session_destroy() ;
                    # Lignes suivantes pour DEBUG
                    if(isset($_SESSION['id'])) {
                        if($_SESSION['id'] == $checkMail['id_user']) {
                            error_log("User.php, methode connexion : utilisateur est deja connecte") ;
                        } else {
                            error_log("User.php, methode connexion : un nouvel utilisateur se connecte") ;
                        }
                    }
                    else {
                        error_log("User.php, methode connexion : Pas de variable de session pour la session en cours") ;
                    }
                    # Fin lignes DEBUG

                    #if(session_status() == PHP_SESSION_ACTIVE) error_log("Session deja active");
                    $_SESSION['id'] = $checkMail['id_user'];
                    $_SESSION['name'] = $checkMail['name'];
                    $_SESSION['surname'] = $checkMail['firstname'];
                    $_SESSION['mail'] = $mail;

                    # Gestion du cas ou un utilisateur se reconnecte sans qu'il y ait eu deocnnexion
                    # Ne doit pas se produire sauf si acces direct a la page de connexion
                    # Accepte, mais il faut supprimer les types d'utilisateur (Client, Freelance...)
                    # positionnes par l'utilisateur precedent pour ne pas recuperer ses valeurs

                    # Recherche de tous les types d'utilisateur configures dans la base type
                    $listeDesTypesUtilisteur=$this->_bdd->query('SELECT name FROM type');
                    foreach  ($listeDesTypesUtilisteur as $typeExistant) {
                        $nameTypeExistant=$typeExistant['name'];
                        error_log("User.php, methode connexion : suppression si positionne de  $nameTypeExistant") ;
                        # Si le type est positionne dans la session, on le supprime
                        if (isset($_SESSION[$nameTypeExistant])) unset($_SESSION[$nameTypeExistant]) ;
                    }
                    #$req->bindParam(':utilisateur', $checkMail['id_user']);
                    #$req->execute();
                    #$listUserTypeOfUser = $req->fetchall();





                    # On recherche tous les types d'utilisateur de l'utilisateur qui vient de se connecter
                    # pour lui attribuer ses types utilisateur (etape qui suit)
                    $req = $this->_bdd->prepare('SELECT t.id_type, t.name FROM type AS t join user_type AS ut ON t.id_type=ut.id_type WHERE ut.id_user= :utilisateur');
                    $req->bindParam(':utilisateur', $checkMail['id_user']);
                    $req->execute();
                    $listUserTypeOfUser = $req->fetchall();


                    # Puis on positionne pour l'utilisateur les types utilisateur qui lui sont attribues
                    # Freelance, Client (plusieurs types sont autorises pour un meme utilisateur)
                    foreach($listUserTypeOfUser as $userType){
                        $typeUtilisateur=$userType['name'] ;
                        $idTypeUtilisateur=$userType['id_type'] ;
                        error_log("User.php, methode connexion : type utilisateur $typeUtilisateur") ;
                        $_SESSION[$typeUtilisateur] = $idTypeUtilisateur;
                        }

                    # DEBUG
                    $id_debug=$checkMail['id_user'];
                    $surname_debug=$checkMail['firstname'] ;
                    error_log("User.php, methode connexion : Connexion avec ID $id_debug et SESSION surname  $surname_debug") ;
                    /* Redirection vers la page admin.php
                    Il de doit y avoir aucun echo avant cette commande sinon la redirection ne fonctionnera pas.
                    */
                    header('location: admin.php');
                    return "Vous êtes connecté";
                } else {
                    $this->_mail = $mail;
                    error_log( "User.php, methode connexion : Mot de passe incorrect" );
                    return "Mot de passe incorrect";
                }
            } else {
                error_log( "User.php, methode connexion : Adresse mail inconnue" );
                return "Adresse mail inconnue";
            }
        }
}

// $user = new User ($bdd);
// //$user->createUser('yoyo','asticot','yoastico@gmail.com','papayoyo','img.pjg',3,3);
// //$user->updateUser(8,'name','firstname','mail','avatar',1,2);
// //$user->deleteUser(7);
// //$user->passChange(9, 'papayoyo', 'papayoyo');
// //$test = $user->connexion('yoastico@gmail.com','papayoyo');

// //$result = $user->getUser(9);
// $result = $user->getAllUser();

// echo '<pre>';
// //print_r($test);
// echo '<br>';
// print_r($result);
// echo '</pre>';


