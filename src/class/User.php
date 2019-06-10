<?php
/* ----------------------------------------------------------------------
    Classe User :
    Argument = référence à $bdd de l'init.php passée au constructeur
   -------------------------------------------------------------------- */

class User
{
    private $_password;
    private $_bdd;

    function __construct($bdd)
    {
        $this->_bdd = $bdd;
    }





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

            if (!empty($ubs_listskill)) {
                $requete_cherche_f .=  "GROUP BY j.id_user " ;
                $requete_cherche_f .=  "HAVING count(*) = " ;
                $requete_cherche_f .= count($ubs_listskill) ;
            }

        $result=array();
            // on ajoute chaque utilisateur ayant toutes les compétences
        // dans un tableau result qu'on va renvoyer
        // On renvoie un tableau des utilisateurs ayant toute les competences selectionnes
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
        $req->bindParam(':ci_id_project', $ci_id_project);
        $req->execute();
        $result = $req->fetchAll();
        //var_dump($result);

        return $result;

    }

/*
 * Création d'un utilisateur
 * Met a jour la table users
 * et la table user_type en fonction des profils (Client, Freelance) sélectionnés
 */
    function createUser($cu_name, $cu_firstname, $cu_mail, $cu_password, $cu_enterprise_name, $cu_siret, $cu_city, $cu_iban, $cu_phone)
    {
        $this->_password = password_hash($cu_password, PASSWORD_DEFAULT);
        $req = $this->_bdd->prepare("INSERT INTO users (name, firstname, mail, password, enterprise_name, siret, city, iban, phone)
                                    VALUES (:name, :firstname, :mail, :password, :enterprise_name, :siret, :city, :iban, :phone)");
        $req->bindParam(':name', $cu_name);
        $req->bindParam(':firstname', $cu_firstname);
        $req->bindParam(':mail', $cu_mail);
        $req->bindParam(':password', $this->_password);
        $req->bindParam(':enterprise_name', $cu_enterprise_name);
        $req->bindParam(':siret', $cu_siret);
        $req->bindParam(':city', $cu_city);
        $req->bindParam(':iban', $cu_iban);
        $req->bindParam(':phone', $cu_phone);
        if ($req->execute()) {
            echo "Succes";
            $user_last_id = $this->_bdd->lastInsertId();
        } else {
            $erreur_insert=$req->errorInfo() ;
            if ($erreur_insert['0'] == '23000'){
                return "DUPLICATE_REC";
            }
        };
        if (empty($_POST['user_type'])) {
            $_POST['user_type']=array(2);
        }
        foreach ($_POST['user_type'] as $type) {
            $req = $this->_bdd->prepare("INSERT INTO user_type (id_type, id_user)
                                    VALUES (:id_type, :id_user)");

            $req->bindParam(':id_type', $type);
            $req->bindParam(':id_user', $user_last_id);
            if ($req->execute()) {

                return "succés";
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
        $req = $this->_bdd->prepare("UPDATE users
                                    SET name=:name, firstname=:firstname, enterprise_name=:enterprise_name,
                                    siret=:siret, city=:city, iban=:iban, phone=:phone
                                    WHERE id_user=:id_user");


        $req->bindParam(':id_user', $id_user);
        $req->bindParam(':name', $ud_name);
        $req->bindParam(':firstname', $ud_firstname);
        $req->bindParam(':enterprise_name', $ud_enterprise_name);
        $req->bindParam(':siret', $ud_siret);
        $req->bindParam(':city', $ud_city);
        $req->bindParam(':iban', $ud_iban);
        $req->bindParam(':phone', $ud_phone);


        if ($req->execute()) {
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

                $req->bindParam(':id_type', $ud_user_type);
                $req->bindParam(':id_user', $id_user);

                if ($req->execute()) {
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

 /*
  * Fonction de connexion au site,
  * Appelé par login.php, on select l'id user et toute ses informations en recherchant en bdd, sur le mail utilisateur, on vérifie
  * les données POST entrée dans le formulaire avec celle en base de données
  * On vérifie que le hash du password saisi correspond bien au hash du password en bdd
  * On renseigne les variables de session pour l'utilisateur en cours (id_user, name, surname, mail) qui seront utilisés sur le site
  * le temps de la connexion
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
                    # en l'état il y a 1=freelance, 2=client.
                    $listeDesTypesUtilisteur=$this->_bdd->query('SELECT name FROM type');
                    # On reinitialise toutes les variables d'environnement indiquant les types du user
                    # On gère un accés à la page de connexion sans qu'il y ait eu de deco préalable
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
                    # les variables ont étés préalablement mises à zero
                    foreach($listUserTypeOfUser as $userType){
                        $typeUtilisateur=$userType['name'] ;
                        $idTypeUtilisateur=$userType['id_type'] ;
                        error_log("User.php, methode connexion : type utilisateur $typeUtilisateur") ;
                    # Pour un freelance, la ligne suivante correspond à faire
            # exemple : $_SESSION['Freelance']    =           1
                        $_SESSION[$typeUtilisateur] = $idTypeUtilisateur;
                        }
                    /* Redirection vers la page admin.php
                    Il de doit y avoir aucun echo avant cette commande sinon la redirection ne fonctionnera pas.
                    */
                    header('location: admin.php');
                    return "Vous êtes connecté";
                } else {
                    # Comme on est dans la condition du checkpass, le mail est dejà vérifié
                    # la vérification du password a échoué (checkpass= password_verify),
                    # on renvoie Mot de passe incorrect.
                    error_log( "User.php, methode connexion : Mot de passe incorrect" );
                    //header('location:admin.php'); // AJOUT
                    return "Mot de passe incorrect";
                }
            } else {
                # Sinon l'adresse mail n'est pas connue dans la bdd.
                error_log( "User.php, methode connexion : Adresse mail inconnue" );
                //header('location:admin.php'); // AJOUT
                return "Adresse mail inconnue";
            }
        }
}

