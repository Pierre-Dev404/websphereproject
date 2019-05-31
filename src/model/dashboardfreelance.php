<?php
// Les traitements qui suivent sont executes qu'il y ait des donnees POST ou non !!


// Formulaire de création de projet
// On (re)affiche le formulaire de creation de projet dans tous les cas
error_log("model dashboardfreelance.php : ENTREE MODELE");
$menuclientorfreelance="<ul>";
if (isset($_SESSION['Freelance'])) {
    error_log("model dashboardfreelance.php : ON EST BIEN FREELANCE");
    $menuclientorfreelance .= '

        <li><a href="/websphereProject/src/?p=dashboardC">Dashboard client</a></li>
    
       ';

    $resultskill = new Skill ($bdd);
    $skill = $resultskill->getSkills();
    $form_insert_skill = "";

            $form_insert_skill.="<h2> Ajouter des compétences </h2>";
            foreach ($skill as $element) {
                $form_insert_skill .= '
               <label> <input class="c" type="checkbox" class="css-checkbox" name="user_skill[]" value=' . $element['id_skill'] . ' />' . $element['name'] . '<br></label>
                ';

            }

    if (!empty($_POST['user_skill'])) {
        // On a ajoute des compétences avec le formulaire ...  Ajouter des compétences
        $skills = new Skill($bdd);
        $user = $_SESSION['id'];
        $valueksill=$_POST['user_skill'];
        $resultinsertskill = $skills->insertSkills($user, $valueksill);
    }


    error_log("model dashboardfreelance.php : Appel getProjectProposeToFreelance");
    $myproject= new Project($bdd);
    $id_user=$_SESSION['id'];
    $resultgetproject = $myproject->getProjectProposeToFreelance($id_user, '1');

    $mesprojets_default_message_proposed = "<p>Vous n'avez aucun projet proposé en cours</p></p>";
    $mesprojetsproposes=$mesprojets_default_message_proposed;

    $mesprojetsproposes .= ' <div class="projet">';
    foreach($resultgetproject as $elementproject){
        if ($mesprojetsproposes == $mesprojets_default_message_proposed) {
            error_log("model dashboardclient.php :  variable mesprojetsproposes est a la valeur par défaut, on la vide");
            $mesprojetsproposes = '';
        }
        $mesprojetsproposes .= '

    <div class="allproject">
            <p> Titre: ' . $elementproject['title'] . '</p>
            <p> Prix: ' . $elementproject['price'] . '</p>
            <!-- <p> IDP ' . $elementproject['id_project'] . '</p> -->
            <p> Date de début ' . $elementproject['content'] . '</p>
            
            <form role="form" method="post">
                <input  type="hidden" name="gpt_id_project" value="'. $elementproject['id_project'].'">
                <button class="btn btn-primary" type="submit">Accepter le projet</button>     
            </form>
    </div>
        ';

    }
    $mesprojetsproposes .= ' </div>';


    error_log("model dashboardfreelance.php : Appel getProjectProposeToFreelance");





    $myproject= new Project($bdd);
    $id_user=$_SESSION['id'];
    $resultgetprojectaccepted = $myproject->getProjectProposeToFreelance($id_user, '2');
    error_log("model dashboardfreelance.php : Constitution de mes projets acceptes");

    $mesprojets_acceptes_default_message="<p>Vous n'avez aucun projet accepté par un client en cours</p>";
    $mesprojetsacceptes= $mesprojets_acceptes_default_message;

    $getinfo = new User ($bdd);
    $mesprojetsacceptes .='<div class="projet">';
    foreach($resultgetprojectaccepted as $elementprojectaccept){

        if ($mesprojetsacceptes == $mesprojets_acceptes_default_message) {
            error_log("model dashboardclient.php :  variable mesprojetsacceptes est a la valeur par défaut, on la vide");
            $mesprojetsacceptes = '';
        }

        // Recuperation infos complementaires

        $id_user_contact=$_SESSION['id'];
        //$info_id_project=$elementprojectaccept['id_project'];
        //$info_id_project=$_POST['acpt_id_project'];
        $resultcontactinfo = $getinfo->getConctactInfoFromClient($elementprojectaccept['id_project']);


        $mesprojetsacceptes .='

<div class="allproject">
        <p> Titre: ' . $elementprojectaccept['title'] . '</p>
        <p> Prix: ' . $elementprojectaccept['price'] . '</p>
       <!-- <p> IDP ' . $elementprojectaccept['id_project'] . '</p> -->
        <p> Résumé: ' . $elementprojectaccept['content'] . '</p> <br>
        <p> Vous venez d\'accepter le projet: ' . $elementprojectaccept['title'] . ', contactez le client: </p>
        <p> Mail: ' . $resultcontactinfo['mail'] . '</p>
        <p> Téléphone: ' . $resultcontactinfo['phone'] . '</p>
       
        <form role="form" method="post">
            <input  type="hidden" name="acpt_id_project" value="'. $elementprojectaccept['id_project'].'">
            <button  class="btn btn-primary" name="popup" type="submit">Declarer le projet terminé</button>     
        </form>
</div>
        ';
    }
    $mesprojetsacceptes .= ' </div>';


    $myproject= new Project($bdd);
    $id_user=$_SESSION['id'];
    $resultgetproject_termine = $myproject->getProjectProposeToFreelance($id_user, '3');

    $mesprojets_a_valider_default_message="<p>Pas de projet en attente de validation</p>";
    $mesprojetsavalider=$mesprojets_a_valider_default_message;

    $mesprojetsavalider .='<div class="projet">';
    foreach($resultgetproject_termine as $elementproject_termine){
        if ($mesprojetsavalider == $mesprojets_a_valider_default_message) {
            error_log("model dashboardclient.php :  variable mesprojetsavalider est a la valeur par défaut, on la vide");
            $mesprojetsavalider = '';
        }

        $mesprojetsavalider .= '
<div class="allproject">
        <p> Titre: ' . $elementproject_termine['title'] . '</p>
        <p> Prix: ' . $elementproject_termine['price'] . '</p>
       <!-- <p> IDP ' . $elementproject_termine['id_project'] . '</p> -->
        <p> Date de début: ' . $elementproject_termine['content'] . '</p>
</div>
        ';

    }
    $mesprojetsavalider .='</div>';
    if (empty($mesprojetsavalider)){
        error_log("model dashboardfreelance.php : Pas de projets termines");
    }


    $id_user=$_SESSION['id'];
    $resultgetproject_termine = $myproject->getProjectProposeToFreelance($id_user, '4');
    // var_dump($resultgetproject);
    $mes_projets_termines_default_message="<p>Pas de projets validés et terminés </p>";
    $mesprojetstermine=$mes_projets_termines_default_message;

    $mesprojetstermine .='<div class="projet">';
    foreach($resultgetproject_termine as $elementproject_termine){
        if ($mesprojetstermine == $mes_projets_termines_default_message) {
            error_log("model dashboardclient.php :  variable mesprojetstermine est a la valeur par défaut, on la vide");
            $mesprojetstermine = '';
        }

        $mesprojetstermine .= '
<div class="allproject">
        <p> Titre: ' . $elementproject_termine['title'] . '</p>
        <p> Prix: ' . $elementproject_termine['price'] . '</p>
        <!-- <p> IDP ' . $elementproject_termine['id_project'] . '</p> -->
        <p> Date de début: ' . $elementproject_termine['content'] . '</p>
</div>
        ';

    }
    $mesprojetstermine .='</div>';
    if (empty($mesprojetstermine)){
        error_log("model dashboardfreelance.php : Pas de projets termines");
    }











}

if(!empty($_POST)) {

    error_log("model dashboardfreelance.php : Le POST n'est pas vide mais c'est peut etre le POST user_skill");
    if (!empty($_POST['gpt_id_project'])){
        error_log("model dashboardfreelance.php : Le POST n'est pas vide et on vient d'un formulaire acceptation");
        $gpt_id_project = $_POST['gpt_id_project'];
        $project = new Project($bdd);
        error_log("model dashboardfreelance.php : appel methode acceptProjectandDeleteOtherF de Project ");
        $result = $project->acceptProjectandDeleteOtherFl($id_user, $gpt_id_project);
        error_log("model dashboardfreelance.php : Sortie de methode acceptProjectandDeleteOtherF de Project ");
        error_log("model dashboardfreelance.php : SORTIE APRES ASSIGNATION PROJET");

    }
    header('location:?p=dashboardF');



    if (!empty($_POST['acpt_id_project'])){
        error_log("model dashboardfreelance.php : Le POST n'est pas vide et on vient d'un formulaire declarer projet termine");
        error_log("model dashboardfreelance.php : ON EST SUR LE RETOUR POST");
        $acpt_id_project = $_POST['acpt_id_project'];
        $project = new Project($bdd);
        error_log("model dashboardfreelance.php : appel methode setToValidateProjectByClient de Project ");
        $result = $project->setToValidateProjectByClient($id_user, $acpt_id_project);
        error_log("model dashboardfreelance.php : Sortie de methode setToValidateProjectByClient de Project ");
        error_log("model dashboardfreelance.php : SORTIE APRES setToValidateProjectByClient");






        header('location:?p=dashboardF');



    } else {
        // rien a faire

    }

    //$msg = "Tous les champs ne sont pas remplis";
}

    if (isset($_SESSION['Freelance'])) {
        $menuclientorfreelance .= '

        <li><a href="/websphereProject/src/?p=dashboardF">Dashboard freelance</a></li>
        ';

    }
    $menuclientorfreelance.="</ul>";
