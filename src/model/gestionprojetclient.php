<?php
/**
 * Created by PhpStorm.
 * User: pierremeunier
 * Date: 2019-05-24
 * Time: 15:42
 */
error_log("model/gestionprojetclient.php : Entree script");
if (!empty($_POST)) {

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
    $userbyskill = 'init';
    if (isset($_POST['nm_id_project'])) {
        // isset($_POST['nm_id_project'])
        error_log("model/gestionprojetclient.php : On est appelle depuis le dashboard client");
        // on arrive depuis le dashboard client
        // Les donnees POST ne sont as issues du formulaire en cours
        // On gere comme le empty($_POST) des autres formulaires
        $nm_id_project = $_POST['nm_id_project'];
        error_log("model/gestionprojetclient.php : le id project est connu : $nm_id_project");
        $nm_title = $_POST['nm_title'];
        $nm_start_date = $_POST['nm_start_date'];
        $nm_end_date = $_POST['nm_end_date'];
        $nm_price = $_POST['nm_price'];
        $nm_content = $_POST['nm_content'];
        $nm_status_name = $_POST['nm_status_name'];
        $nm_id_project_status = $_POST['nm_idProjectStatus'];


        // On construit  le formulaire de recherche de Freelance
        // si le projet est a l'etat



        if ($nm_id_project_status = 1) {
            // On recherche les Freelance eligibles
            $nm_menu_contextuel_projet= new User($bdd) ;
            $result = $nm_menu_contextuel_projet->getAllFreelance();
            $nm_menu_contextuel_projet = '' ;

            foreach($result as $element) {
                $nm_menu_contextuel_projet .= '    
    
    <form role="form" method="post">
            <div class="allproject">
                    <p>' . $element['name'] . '</p>
                    <p>' . $element['firstname'] . '</p>
                    <p>' . $element['mail'] . '</p>
                    <p>' . $element['enterprise_name'] . '</p>
                    <input type="checkbox"> Chercher Freelance !!! </input>
            </div>
    </form>
            ';
            }




        }



    } else {
        // PAS isset($_POST['nm_id_project'])
        error_log("model/gestionprojetclient.php : On est appelle depuis formulaire cherche freelance");
        if (!isset($nm_id_project)) {
            error_log("model/gestionprojetclient.php : Aie aie aie on a perdu le nm_id_project");
        } else {
            error_log("model/gestionprojetclient.php : Ouf on a bien le nm_id_project $nm_id_project");
        }
        $user = new User($bdd);
        $result = $user->getAllFreelance();
        $userbyskill = "";
        foreach ($result as $element) {

            $userbyskill .= '

<

        ';
        }
        /* On a des donnees envoyees par le formulaire
         * de recherche de Freelance
        */

    }
} else {
    error_log("model/gestionprojetclient.php : Il n'y a PAS de  Post...BIZARRE !!!");
}