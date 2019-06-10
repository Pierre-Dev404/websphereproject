<?php
error_log("model home.php : ENTREE");
$formulaire_client = "";
$formulaire_creation_projet = "";
$menuclientorfreelance="";
$buttonrechercheC="";
$buttonrechercheF= "";
$listcomp="";
$titre_comp="";


$message_pop_up='<div id="pop-up" class="modal">
            <h3>Client :</h3>
            <p> Vous êtes client et souhaitez faire réaliser un projet web ?<br>
                Quelques étapes à suivre : <br>
                1- Allez sur votre dashboard client, créez un projet en remplissant les champs indiqués et postez le.<br>
                2- Après création, cliquez sur \"gérer votre projet\" depuis cette page, vous pourrez choisir un ou plusieurs freelances selon <br>
                les compétences souhaitées à la réalisation. <br>
                3- Attendez qu\'un freelance accepte votre projet pour pouvoir vous mettre en relation.
            </p>

            <h3>Freelance :</h3>
            <p> Si vous êtes freelance, consultez votre dashboard régulièrement pour voir si vous avez des projets proposés <br>
                afin de les accepter.
                Le cas échéant, vous pourrez accéder aux coordonnées de votre interlocuteur.
            </p>
            <a href="#" rel="modal:close">Close</a>
        </div>';


    if (isset($_SESSION['Client'])) {
        error_log("Model home.php POST EMPTY On est Client");
        $resultskill = new Skill ($bdd);
        $skill = $resultskill->getSkills();
        $listcomp = '<ul>';
       $titre_comp =' <h2 class="titre_comp">Les différentes compétences disponibles</h2>';
        foreach ($skill as $element) {
            $listcomp .= '
        
                <li value=' . $element['id_skill'] . ' /> '.'-'.' ' . $element['name'] . ' </li>
               ';
        }
        $listcomp .= '</ul>';
        error_log("Model home.php On a renseigne listcom : $listcomp");

        $menuclientorfreelance .= '
        <a href="/websphereProject/src/?p=dashboardC">Dashboard client</a>
        ';

        $buttonrechercheC = '
        <div class="choix">
                    <a href="/websphereProject/src/?p=dashboardC">
                        <button type="button" class="btn btn-lg"><p>Client : Créer un projet, trouver un freelance</p></button>
                    </a>
                </div>';
        error_log("Model home.php On a renseigne buttonrechercheC : $buttonrechercheC");
    }

if (isset($_SESSION['Freelance'])) {
    $buttonrechercheF = '
    <div class="choix">
        <a href="/websphereProject/src/?p=dashboardF">
            <button type="button" class="btn btn-lg"><p>Freelance : Ajouter des compétences</p></button>
        </a>
    </div>
';

    $menuclientorfreelance .='

        <a href="/websphereProject/src/?p=dashboardF">Dashboard freelance</a>
        ';

}