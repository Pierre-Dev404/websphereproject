<?php
/**
 * Created by PhpStorm.
 * User: pierremeunier
 * Date: 2019-05-24
 * Time: 15:42
 */
error_log("model/gestionprojetclient.php : Entree script");
if (!empty($_POST))
{
    // !empty($_POST)

    /*
    *  Il a des donnes POST envoyees par un formulaire
    *  Ces donnees POST proviennent soit de l'appel
    * soit d'un formulaire Gerer Projet genere par la lecture des projets utilisateur
    * On se sert des champs envoyes pour tester la nature des donnees recues
    * On recupere les champs du formulaire pour creer l'objet Project
    * et appeler la methode createProject ...
    *
    */
    // $cr_title,$cr_content,$cr_start_date,$cr_end_date,$cr_price,$cr_id_project_status
    error_log("model/gestionprojetclient.php : Il ya du Post...normal !!!");



    // On construit  le formulaire de recherche de Freelance
    // si le projet est a l'etat 1
    // On le fait a chaque fois pas seulement quand on arrive de dashboard client

    if ( $_SESSION['nm_idProjectStatus'] = 1 ) {
        // $nm_id_project_status = 1
        error_log("model/gestionprojetclient.php : Le project status est a 1  on cree un formulaire de recherche Freelance");

        $resultskill = new Skill ($bdd);
        $skill = $resultskill->getSkills();
        $listskill = '<form role="form" method="post" action="?p=gestionprojetC">';
        foreach ($skill as $element) {
            $listskill .= '
                <input class="user" type="checkbox" name="user_skill[]" value=' . $element['id_skill'] . ' />' . $element['name'] . '<br>
                ';

        }
        $listskill .= '<button type="submit"> Rechercher </button>' ;
        $listskill .= '</form>' ;
        error_log("model/gestionprojetclient.php : Le project status est a 1  formulaire de recherche Freelance : $listskill");
        // FIN $nm_id_project_status = 1 ( voir plus tard or $nm_id_project_status = 2 )
    }


    error_log("model/gestionprojetclient.php : On va tester si on a du POST nm_id_project qui signifie on est appelle depuis le dashboard client");
    $nm_userbyskill = 'init';
    $_SESSION['nm_userbyskill'] = $nm_userbyskill;
    if (isset($_POST['nm_id_project'])) {
        // isset($_POST['nm_id_project'])

        // DASHBOARD CLIENT
        // isset($_POST['nm_id_project'])
        error_log("model/gestionprojetclient.php : On est appelle depuis le dashboard client");
        // on arrive depuis le dashboard client
        // Les donnees POST ne sont as issues du formulaire en cours
        // On gere comme le empty($_POST) des autres formulaires
        $_SESSION['$nm_id_project'] = $_POST['nm_id_project'];
        $_SESSION['nm_title'] = $_POST['nm_title'];
        $_SESSION['nm_start_date'] = $_POST['nm_start_date'];
        $_SESSION['nm_end_date'] = $_POST['nm_end_date'];
        $_SESSION['nm_price'] = $_POST['nm_price'];
        $_SESSION['nm_content'] = $_POST['nm_content'];
        $_SESSION['nm_status_name'] = $_POST['nm_status_name'];
        $_SESSION['nm_idProjectStatus'] = $_POST['nm_idProjectStatus'];
        //$DBG_userbyskill=$_SESSION['nm_userbyskill'] ;
        //error_log("model/gestionprojetclient.php : Valeur usersbyskill: $DBG_userbyskill");


// FIN isset($_POST['nm_id_project'])
    } else {
    // PAS isset($_POST['nm_id_project'])

        if (isset($_POST['user_skill'])) {
            error_log("model/gestionprojetclient.php : On est appelle depuis formulaire cherche freelance par user_skill");
            // On suppose pour l'instant qu'on arrive du formulaire de recherche de Freelance
            $type = new User ($bdd);
            if (isset($_POST['user_skill'])) {
                $fl_listskill = $_POST['user_skill'];
            } else {
                $fl_listskill = array();
            }
            $result = $type->UserBySkills($fl_listskill);
            error_log("model/gestionprojetclient.php : On a recupere la liste des users en fonction de leurs competences");
            error_log("model/gestionprojetclient.php : On genere le formulaire de choix des Freelance");
            $nm_usersbyskill = '<form role="form" method="post"  action="?p=gestionprojetC"> ';
            if (!empty($result)) {
                // !empty($result)
                foreach ($result as $freelance) {
                    // foreach ($result as $freelance)
                    $nm_usersbyskill .= '
                        <div class="free">
                        Choisir <input class="user" type="checkbox" name="freelance_check[]" value=' . $freelance['id_user'] . ' /> <br>
                        <p>' . $freelance['id_user'] . '</p>
                        <p>' . $freelance['name'] . '</p>
                        <p>' . $freelance['firstname'] . '</p>
                        <p>' . $freelance['enterprise_name'] . '</p>
                        <p>' . $freelance['city'] . '</p>
                        </div>
                        ';
                }
                $nm_usersbyskill .= '<button type="submit" name="choixF" class="btn btn-primary">Selectionner Freelances</button>';
                $nm_usersbyskill .= '</form>';
                $_SESSION['nm_userbyskill'] = $nm_usersbyskill;
            }
        } else {
            error_log("model/gestionprojetclient.php : On n' est PAS appelle depuis formulaire cherche freelance par user_skill");
            if (isset($_POST['freelance_check'])) {
                error_log("model/gestionprojetclient.php : On est appelle depuis formulaire CHOIX freelance");
                $checkboxes = isset($_POST['freelance_check']) ? $_POST['freelance_check'] : array();
                foreach($_POST['freelance_check'] as $value) {
                    error_log("model/gestionprojetclient.php : Freelance check $value");
                }
            }
        }
    }
    /* On a des donnees envoyees par le formulaire
    * de recherche de Freelance
    */
// fin !empty($_POST)
} else {
    //ALT !empty($_POST)
    // On a obligatoirement du POST car in arrive du dashbooard client en confitions normales
    error_log("model/gestionprojetclient.php : Il n'y a PAS de  Post...BIZARRE !!!");
    //FIN ALT !empty($_POST)
}