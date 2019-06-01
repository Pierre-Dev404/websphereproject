<?php
/* ----------------------------------------------------------------------
    Classe User :
    Argument = référence à $bdd de l'init.php passée au constructeur

   -------------------------------------------------------------------- */

class User
{
    /*
    private $_id;
    public $_name;
    public $_firstname;
    public $_mail;
    private $_password;
    private $_status;
    private $_civilite;
    private $_avatar;
    private $_verifyPass;
    */
    private $_bdd;


    //MODIFIE On ne passe que $bdd function __construct($bdd, $id = null)
    function __construct($bdd)
    {
        $this->_bdd = $bdd;
        /*
         * A SUPPRIMER NE SERT PAS
         * if($id != NULL){
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
        */
    }


/*  A SUPPRIMER  Fonction intégrée à la méthede updateUser()
    function updateProfil($_id_user, $_type)
    {

        error_log("La valeur de type traitee est $_type ");
        $req = $this->_bdd->prepare("INSERT INTO user_type (id_type, id_user)
                                    VALUES (:id_type, :id_user)");
        if ($req->execute(array(
            'id_type' => $_type,
            'id_user' => $_id_user
        ))) {
            echo "Succes";
        } else {
            echo "une erreur est survenue lors de l'insertion dans usertype";
            print_r($req->errorInfo());
        }
    }
*/





/* A SUPPRIMER LC

    function getUserLC($id)
    {
        $req = $this->_bdd->prepare("SELECT nom, prenom, mail, password, avatar, id_civilite, id_user_status 
                                FROM user
                                WHERE id=:id");
                    $req->bindParam(':id', $id);
                    $req->execute();
                    $result = $req->fetch();
                    return $result;
    }
*/


// Méthode userBySkills()
// Prend en argument un array  de liste de compétence renvoyé par le formulaire de recherche Freelance
// Si on recoit un array vide on cherchera pour n'importe quelles compétences
// On recherche les infos utilisateur (id_user, name, firstname, enterprise_name, city , id_skill)
// par une jointure sur la colonne id_user entre la table users et la table users_skill
// Dans la 'table' renvoyée on récupere les nfos utilisateur
// avec comme critere (WHERE) la compétence correspond à une des compétences passées en parametre
// Comme il faut qu'il y ait toutes les compétences
// On groupe par utilisateur (GROUP BY)
// Et on regarde si le nombre de compétences de l'utilisatueur qui correspondent aux prérequis
// sont bien au complet (le nombre de compétences de l'utilisateur correspondant aux compétences requises
// correspond au nombre total de compétences = il n'en manque pas )
// Si aucune  compétences n'est  passées :
// On n'effectue pas la vérification sur le nombre de compétences et on n'ajoute pas kles clauses
// GROUP_BY / HAVING
//
    function userBySkills($ubs_listskill)
    {
        $requete_cherche_f =   "SELECT DISTINCT j.id_user, j.name, j.firstname, j.enterprise_name , j.city  FROM 
                              (
                                SELECT  u.id_user, u.name, u.firstname, u.enterprise_name, u.city , us.id_skill
                                FROM users u     
                                JOIN user_skill us on u.id_user = us.id_user ";

        $whereclause=" " ;
        if (! empty($ubs_listskill)) {
            // si la liste des competences passees est vide
            // On recheche sans critere de competence
            // Si elle n'est pas vide contruit une requete avec
            // clauses group by et having en verifiant que le nombre de criteres
            // renvoyes est le bon

            $whereclause="WHERE " ;
            foreach ($ubs_listskill as $type) {
                $whereclause.="us.id_skill = $type OR " ;
            }
            $whereclause=substr_replace($whereclause, '', -3, -1) ;

        }
        $requete_cherche_f .= $whereclause ;
        $requete_cherche_f .= " ) j " ;

            if (! empty($ubs_listskill)) {
                $requete_cherche_f .=  "GROUP BY j.id_user " ;
                $requete_cherche_f .=  "HAVING count(*) = " ;
                $requete_cherche_f .= count($ubs_listskill) ;
            }


        // error_log("User.php, methode userBySkills : requete $requete_cherche_f ");
        $result=array();
        foreach  ($this->_bdd->query($requete_cherche_f) as $row) {
            $result= array_merge($result, array($row) );
        }



            return $result;
    }

    /*
    Appelé depuis le dashboard Freelance
    Permet de récupérer des information complémentaires sur le client
    A faire : factoriser avec getConctactInfoFromFreelance
    Ce sont presque les mêmes fonctions
    */
    function getConctactInfoFromClient($ci_id_project)
    {
            $req = $this->_bdd->prepare("SELECT u.name, u.firstname, u.mail, u.phone, w.id_type FROM work w
                                        JOIN project p ON w.id_project = p.id_project
                                        JOIN users u ON u.id_user = w.id_user
                                        WHERE w.id_project=:ci_id_project and w.id_type = 2
                                      ");
           /* $DBG_req="SELECT u.mail, u.phone FROM work w
                                        JOIN project p ON w.id_project = p.id_project
                                        JOIN users u ON u.id_user = w.id_user
                                        WHERE w.id_project=$ci_id_project and w.id_type = 2
                                      "; */
        //error_log("User.php, methode getConctactInfoFromFreelance : requete $DBG_req ");
                    $req->bindParam(':ci_id_project', $ci_id_project);
                    $req->execute();
                    $result = $req->fetch();
        //var_dump($result);

        return $result;

    }

    /*
    Appelé depuis le dashboard Client
    Permet de récupérer des information complémentaires sur le Freelance
    */
    function getConctactInfoFromFreelance($ci_id_project)
    {
        $req = $this->_bdd->prepare("SELECT u.name, u.firstname, u.mail, u.phone, w.id_type FROM work w
                                        JOIN project p ON w.id_project = p.id_project
                                        JOIN users u ON u.id_user = w.id_user
                                        WHERE w.id_project=:ci_id_project and w.id_type = 1
                                      ");
        /* $DBG_req="SELECT u.mail, u.phone FROM work w
                                     JOIN project p ON w.id_project = p.id_project
                                     JOIN users u ON u.id_user = w.id_user
                                     WHERE w.id_project=$ci_id_project and w.id_type = 2
                                   "; */
        //error_log("User.php, methode getConctactInfoFromFreelance : requete $DBG_req ");
        $req->bindParam(':ci_id_project', $ci_id_project);
        $req->execute();
        $result = $req->fetch();
        //var_dump($result);

        return $result;

    }

    // On crée deux fois la même function pour récupérer les infos, car dans le cas où l'on veut juste récupérer
    // un utilisateur ( pour le dashboard client ) on utilise un fetch() mais dans le cas de la gestion de projet
    // on veut afficher TOUS les utilisateurs à qui on a proposé le projet, on utilise donc fetchAll().

    function getAllConctactInfoFromFreelance($ci_id_project)
    {
        $req = $this->_bdd->prepare("SELECT u.name, u.firstname, u.mail, u.phone, w.id_type FROM work w
                                        JOIN project p ON w.id_project = p.id_project
                                        JOIN users u ON u.id_user = w.id_user
                                        WHERE w.id_project=:ci_id_project and w.id_type = 1
                                      ");
        /* $DBG_req="SELECT u.mail, u.phone FROM work w
                                     JOIN project p ON w.id_project = p.id_project
                                     JOIN users u ON u.id_user = w.id_user
                                     WHERE w.id_project=$ci_id_project and w.id_type = 2
                                   "; */
        //error_log("User.php, methode getConctactInfoFromFreelance : requete $DBG_req ");
        $req->bindParam(':ci_id_project', $ci_id_project);
        $req->execute();
        $result = $req->fetchAll();
        //var_dump($result);

        return $result;

    }







/* A SUPPRIMER, ne sert pas en définitive
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
*/


/*
 *  Appelé depuis le create.php
 * Création d'un utilisateur
 * Met a jour la table users
 * et la table user_type en fonction des profils (Client, Freelance) sélectionnés
 */
    function createUser($cu_name, $cu_firstname, $cu_mail, $cu_password, $cu_enterprise_name, $cu_siret, $cu_city, $cu_iban, $cu_phone)
    {

        error_log("User.php, methode createUser : entree methode ");
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
            error_log("User.php, methode Last Id insertion User est  erreur : $user_last_id ");
            // echo($user_last_id);
        } else {
            $erreur_insert=$req->errorInfo() ;
            if ($erreur_insert['0'] == '23000'){
               $disp_error=$erreur_insert['2'];
                error_log("User.php, methode createUser erreur : $disp_error ");
                //echo "une erreur est survenue lors de l'insertion $disp_error";
                return "DUPLICATE_REC";
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
                error_log("insertion OK  $type / $user_last_id");
            } else {
                error_log("une erreur est survenue lors de l'insertion dans usertype ");
                error_log( print_r( $req->errorInfo() ) );

            }


            #return true;
        }
    }





/*
    Méthode appelée depuis le gestionprofil
    Permet de mettre à jour les infos utilisateurs sauf le mail qui ser à se connecter
    et le mot de passe.
    Si la requete en base est ok, met a jour les variables superglobales
    $_SESSION['name'] et $_SESSION['surname'] ainsi que les variables  $_SESSION['Client/freelance']
*/


    function updateUser($id_user, $ud_name, $ud_firstname, $ud_enterprise_name, $ud_siret, $ud_city, $ud_iban, $ud_phone,$ud_user_type)
    {

        $DBG_req="UPDATE users
                        SET name=$ud_name, firstname=$ud_firstname, enterprise_name=$ud_enterprise_name,
                        siret=$ud_siret, city=$ud_city, iban=$ud_iban, phone=$ud_phone
                        WHERE id_user=$id_user" ;

        error_log("User.php, methode update_user : La requete  est $DBG_req ");
        $req = $this->_bdd->prepare("UPDATE users
                                    SET name=:name, firstname=:firstname, enterprise_name=:enterprise_name,
                                    siret=:siret, city=:city, iban=:iban, phone=:phone
                                    WHERE id_user=:id_user");
        if ($req->execute(array(
            'id_user' => $id_user,
            'name' => $ud_name,
            'firstname' => $ud_firstname,
            'enterprise_name' => $ud_enterprise_name,
            'siret' => $ud_siret,
            'city' => $ud_city,
            'iban' => $ud_iban,
            'phone' => $ud_phone
        ))) {
            error_log("User.php, methode update_user : succès updateuser") ;
            // Mise à jour des variables de session
            $_SESSION['name'] = $ud_name;
            $_SESSION['surname'] = $ud_firstname;
        } else {
            error_log("User.php, methode update_user : echec updateuser") ;
            //$erreur_insert=$req->errorInfo() ;
        }
        //  Insertion EVENTUELLE d'une nouvelle ligne pour l'utilisateur dans la table user_type

            if ($ud_user_type == '1' or $ud_user_type == '2') {
                error_log("User.php, methode update_user : La valeur de type traitee est $ud_user_type ");
                $req = $this->_bdd->prepare("INSERT IGNORE INTO user_type (id_type, id_user)
                                    VALUES (:id_type, :id_user)");
                if ($req->execute(array(
                    'id_type' => $ud_user_type,
                    'id_user' => $id_user
                ))) {
                    error_log("User.php, methode update_user : succès updateusertype") ;

                    // On va chercher dans la table type le libelle du type traité
                    // 1 -> Freelance
                    // 2 -> Client
                    // mais permet d'être flexible sur la denomination

                    $req = $this->_bdd->prepare('SELECT name FROM type  WHERE id_type= :id_type');
                    $req->bindParam(':id_type', $ud_user_type);
                    $req->execute();
                    $udt_typename_search = $req->fetch();
                    $udt_typename =  $udt_typename_search['name'] ;

                    // et on met a jour la variable de session associee
                    // On ne gere que l'ajout !!!!!
                    $_SESSION[$udt_typename] = $ud_user_type;
                    error_log("User.php, Mise a jour variable SESSION/$udt_typename avec la valeur $ud_user_type") ;

                } else {
                    error_log("User.php, methode update_user : echec updateusertype") ;
                    error_log(print_r($req->errorInfo())) ;
                }
            } else {
                error_log("User.php, methode update_user : pas de updateusertype demandé") ;
            }


        header('location: ?p=gestionprofil');
    }




    function getUserInfo($gui_id_user){
        $req = $this->_bdd->prepare('SELECT id_user, name, firstname,  enterprise_name, siret, city, iban, phone FROM users WHERE id_user= :id_user');
        $req->bindParam(':id_user', $gui_id_user);
        $req->execute();
        $result_user_info=$req->fetch();
        return $result_user_info ;
    }












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
                    //header('location:admin.php'); // AJOUT
                    return "Mot de passe incorrect";
                }
            } else {
                error_log( "User.php, methode connexion : Adresse mail inconnue" );
                //header('location:admin.php'); // AJOUT
                return "Adresse mail inconnue";
            }
        }
}


/*
        function deleteUserLC($id)
        {
            $req = $this->_bdd->prepare("DELETE FROM user WHERE id=:id");
            $req->execute(array('id' => $id));
            return true;
        }

        function passChangeLC($id, $oldPassword, $newPassword)
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

 function updateUserLilian($id, $name, $firstname, $mail, $avatar, $civilite, $status)
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
*/
