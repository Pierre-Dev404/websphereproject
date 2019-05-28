<?php
// Les traitements qui suivent sont executes qu'il y ait des donnees POST ou non !!


// Formulaire de création de projet
// On (re)affiche le formulaire de creation de projet dans tous les cas
error_log("model dashboardfreelance.php : ENTREE");
$menuclientorfreelance="<ul>";
if (isset($_SESSION['Freelance'])) {
    error_log("model dashboardfreelance.php : ON EST BIEN FREELANCE");
    $menuclientorfreelance .= '

        <li><a href="/websphereProject/src/?p=dashboardC">Dashboard client</a></li>
    
       ';

    $resultskill = new Skill ($bdd);
    $skill = $resultskill->getSkills();
    $form_insert_skill = "";


            foreach ($skill as $element) {
                $form_insert_skill .= '
             
                <input type="checkbox" name="user_skill[]" value=' . $element['id_skill'] . ' />' . $element['name'] . '<br>
                ';

            }

    if (!empty($_POST['user_skill'])) {
        $skills = new Skill($bdd);
        $user = $_SESSION['id'];
        $valueksill=$_POST['user_skill'];
        $resultinsertskill = $skills->insertSkills($user, $valueksill);
    }



    error_log("model dashboardfreelance.php : Appel getProjectProposeToFreelance");
    $myproject= new Project($bdd);
    $id_user=$_SESSION['id'];
    $resultgetproject = $myproject->getProjectProposeToFreelance($id_user, '1');
    // var_dump($resultgetproject);
    $mesprojetsproposes="";
    foreach($resultgetproject as $elementproject){
        $mesprojetsproposes .= '
<div class="allproject">
        <p> TITRE ' . $elementproject['title'] . '</p>
        <p> PRIX ' . $elementproject['price'] . '</p>
        <p> IDP ' . $elementproject['id_project'] . '</p>
        <p> DATE DEBUT ' . $elementproject['content'] . '</p>
        
        <form role="form" method="post">
            <input  type="hidden" name="gpt_id_project" value="'. $elementproject['id_project'].'">
            <button type="submit">Accepter le projet</button>     
        </form>
</div>
        ';

    }




    error_log("model dashboardfreelance.php : Appel getProjectProposeToFreelance");
    $myproject= new Project($bdd);
    $id_user=$_SESSION['id'];
    $resultgetprojectaccepted = $myproject->getProjectProposeToFreelance($id_user, '2');
    // var_dump($resultgetproject);
    $mesprojetsacceptes="";
    foreach($resultgetprojectaccepted as $elementprojectaccept){
        $mesprojetsacceptes .= '
<div class="allproject">
        <p> TITRE ' . $elementprojectaccept['title'] . '</p>
        <p> PRIX ' . $elementprojectaccept['price'] . '</p>
        <p> IDP ' . $elementprojectaccept['id_project'] . '</p>
        <p> DATE DEBUT ' . $elementprojectaccept['content'] . '</p>
        
        <form role="form" method="post">
            <input  type="hidden" name="acpt_id_project" value="'. $elementprojectaccept['id_project'].'">
            <button type="submit">Declarer le projet terminé</button>     
        </form>
</div>
        ';

    }


   


}
if(!empty($_POST)) {
    error_log("model dashboardfreelance.php : Le POST n'est pas vide mais c'est peut etre le POST user_skill");
    if (!empty($_POST['gpt_id_project'])){
        error_log("model dashboardfreelance.php : Le POST n'est pas vide et on vient d'un formulaire acceptation");
        $gpt_id_project = $_POST['gpt_id_project'];
        $project = new Project($bdd);
        error_log("model home.php : appel methode acceptProjectandDeleteOtherF de Project ");
        $result = $project->acceptProjectandDeleteOtherFl($id_user, $gpt_id_project);
        error_log("model home.php : Sortie de methode acceptProjectandDeleteOtherF de Project ");
        error_log("model create.php : SORTIE APRES ASSIGNATIN PROJET");

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
