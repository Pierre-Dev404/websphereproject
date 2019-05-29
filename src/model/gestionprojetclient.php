<?php
/**
 * Created by PhpStorm.
 * User: pierremeunier
 * Date: 2019-05-24
 * Time: 15:42
 */
error_log("model/gestionprojetclient.php : Entree script");


$menuclientorfreelance="<ul>";
if (isset($_SESSION['Freelance'])) {
    error_log("model dashboardfreelance.php : ON EST BIEN FREELANCE");
    $menuclientorfreelance .= '

        <li><a href="/websphereProject/src/?p=dashboardC">Dashboard client</a></li>
    
       ';
}

if (isset($_SESSION['Freelance'])) {
    $menuclientorfreelance .= '

        <li><a href="/websphereProject/src/?p=dashboardF">Dashboard freelance</a></li>
        ';

}




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
    // la condition est que  le projet soit a l'etat 1
    // On le fait a chaque fois pas seulement quand on arrive de dashboard client

    $listskill = ''; // Evite erreur dans controleur si valeur non pertinente dans contexte
    // if (xx $_SESSION xx ['nm_idProjectStatus'] xx = xx 1 ) {   ... 2 erreurs dans cette ligne !!!

    /* MIGRE EN FIN DE PAGE
    if ( $_SESSION['nm_idProjectStatus'] == 1 ) {
        // $nm_id_project_status = 1
        var_dump($_POST);
        error_log("model/gestionprojetclient.php : A chaque fois  on cree un formulaire de recherche Freelance si project status est a 1");


        $resultskill = new Skill ($bdd);
        $skill = $resultskill->getSkills();
        $listskill = '
             <h2> Rechercher un freelance</h2>
            <form role="form" class="form-checkbox" method="post" action="?p=gestionprojetC">';
        foreach ($skill as $element) {
            $listskill .= '
            <div class="user-checkbox">
                <input class="user" type="checkbox" name="user_skill[]" value=' . $element['id_skill'] . ' />' . $element['name'] . '<br>
            </div>
                ';

        }
        $listskill .= '<button type="submit"> Rechercher </button>' ;
        $listskill .= '</form>' ;
        error_log("model/gestionprojetclient.php : Le project status est a 1  formulaire de recherche Freelance : $listskill");
        // FIN $nm_id_project_status = 1 ( voir plus tard or $nm_id_project_status = 2 )
    } else { var_dump($_SESSION) ;}
    */


    error_log("model/gestionprojetclient.php : On va tester si on a du POST nm_id_project qui signifie on est appelle depuis le dashboard client");
    $nm_userbyskill = '';
    $acceptorrefuse='';
    $_SESSION['nm_userbyskill'] = $nm_userbyskill;
    if (isset($_POST['nm_id_project'])) {
        // isset($_POST['nm_id_project'])

        // DASHBOARD CLIENT
        // isset($_POST['nm_id_project'])
        error_log("model/gestionprojetclient.php : On a du POST nm_id_project , on est appelle depuis le dashboard client");
        // on arrive depuis le dashboard client
        // Les donnees POST sont issues du DASHBOARD CLIENT
        // On gere comme le empty($_POST) des autres formulaires
        // On sauvegarde les POST pour reafficher a chaque fois le recapitulatif Projet
        // Sinon le controler n'aurait plus les donnéess lors des autres appels
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



            $nm_usersbyskill = '<form role="form" method="post"> ';
            $nm_usersbyskill .= '<div class="form-dashb"> ';
            if (!empty($result)) {
                // !empty($result)
                $project = new Project ($bdd);
                $id_project= $_SESSION['$nm_id_project'];
                $alreadyAssignedFreelances = $project->getAllAssignedFreelancesByIdProject($id_project);
                //var_dump($alreadyAssignedFreelances);
                foreach ($result as $freelance) {
                    // foreach ($result as $freelance)
                    if (!in_array($freelance['id_user'],$alreadyAssignedFreelances)) {
                        $nm_usersbyskill .= '
                        <div class="free-dash">
                        Choisir <input class="user" type="checkbox" name="freelance_check[]" value=' . $freelance['id_user'] . ' /> <br>
                        <p>' . $freelance['id_user'] . '</p>
                        <p>' . $freelance['name'] . '</p>
                        <p>' . $freelance['firstname'] . '</p>
                        <p>' . $freelance['enterprise_name'] . '</p>
                        <p>' . $freelance['city'] . '</p>
                        </div>
                        ';
                    }
                }
                $nm_usersbyskill .= '</div>';
                $nm_usersbyskill .= '<button type="submit" name="choixF" class="btn btn-primary">Selectionner Freelances</button>';
                $nm_usersbyskill .= '</form>';
                $_SESSION['nm_userbyskill'] = $nm_usersbyskill;
            }
        } else {
            error_log("model/gestionprojetclient.php : On n' est PAS appelle depuis formulaire cherche freelance par user_skill");
            if (isset($_POST['freelance_check'])) {
                error_log("model/gestionprojetclient.php : On est appelle depuis formulaire CHOIX freelance");

                if (isset($_POST['freelance_check'])) {
                    $checkboxes = $_POST['freelance_check'];
                } else {
                    $checkboxes = array();
                }

                $project = new Project ($bdd);
                $id_project= $_SESSION['$nm_id_project'];

                foreach( $checkboxes as $value_id_freelance) {
                    $id_freelance = $value_id_freelance;
                    $result = $project->assignProject($id_project, $id_freelance);
                    //$_SESSION['$nm_id_project']
                    // TRAITEMENT des Freelances selectionnes
                    error_log("model/gestionprojetclient.php : Freelance check $value_id_freelance");
                    error_log("model/gestionprojetclient.php : Freelance  $result");
                }
                header('location: ?p=dashboardC');
            }
        }
    }

    /*
     * MIGRE DEPUIS LE DEBUT DE PAGE
     * pour que $_SESSION['nm_idProjectStatus'] soit valorise dans if (isset($_POST['nm_id_project']))
     */
    if ( $_SESSION['nm_idProjectStatus'] == 1 ) {
        $acceptorrefuse='';
        // $nm_id_project_status = 1
        //var_dump($_POST);
        /*
         * On affiche le formulaire de recherche Freelance a chaue afficahge de la page
         * si le project status est a 1
         * ...contrairement aux autres formulaires dont l'affichage depend du contexte d'appel
         */
        error_log("model/gestionprojetclient.php : A chaque affichage de la page  on cree un formulaire de recherche Freelance si project status est a 1");


        $resultskill = new Skill ($bdd);
        $skill = $resultskill->getSkills();
        $listskill = '
             <h2> Rechercher un freelance</h2>
            <form role="form" class="form-checkbox" method="post" action="?p=gestionprojetC">';
        foreach ($skill as $element) {
            $listskill .= '
            <div class="user-checkbox">
                <input class="user" type="checkbox" name="user_skill[]" value=' . $element['id_skill'] . ' />' . $element['name'] . '<br>
            </div>
                ';

        }
        $listskill .= '<button type="submit"> Rechercher </button>' ;
        $listskill .= '</form>' ;
        error_log("model/gestionprojetclient.php : Le project status est a 1  formulaire de recherche Freelance : $listskill");
        // FIN $nm_id_project_status = 1 ( voir plus tard or $nm_id_project_status = 2 )
    } elseif ( $_SESSION['nm_idProjectStatus'] == 3 ) {
        $nm_userbyskill ='';
        $listskill='';


        $resultskill = new Project ($bdd);
        $acceptorrefuse = '
             <h2> Valider le projet ou refuser </h2>
            <form role="form" class="form-checkbox" method="post" action="?p=gestionprojetC">';

        $acceptorrefuse .= '
            <div class="user-checkbox">
                
                
                <input class="user" type="radio" name="acpt_refuse_project" value="acpt_project" checked/> Accepter <br>
                <input class="user" type="radio" name="acpt_refuse_project" value="refuse_project"/> Refuser <br>
                <button type="submit"> Valider </button>
            </div>
                ';
    }
    if(isset($_POST['acpt_refuse_project'])) {

        // On vient du formulaire acpt_or_refuse project
        if ($_POST['acpt_refuse_project'] == 'acpt_project') {
            $resultacpt_project = new Project ($bdd);
            $id_project_accepted = $_SESSION['$nm_id_project'];
            $acpt_project = $resultacpt_project->validateTerminateProject($id_project_accepted);


        } else {
            $resultrefuse_project = new Project ($bdd);
            $id_project_accepted = $_SESSION['$nm_id_project'];
            $refuse_project = $resultrefuse_project->refuseDelivery($id_project_accepted);
        }
        header('location:?p=dashboardC');
    }


// fin !empty($_POST)
} else {
    //ALT !empty($_POST)
    // On a obligatoirement du POST car in arrive du dashbooard client en confitions normales
    error_log("model/gestionprojetclient.php : Il n'y a PAS de  Post...BIZARRE !!!");
    //FIN ALT !empty($_POST)
}