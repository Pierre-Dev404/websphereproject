<?php
error_log("model home.php : ENTREE");
$formulaire_client = "";
$formulaire_creation_projet = "";
$menuclientorfreelance="";
$buttonrechercheC="";
$buttonrechercheF= "";
$listcomp="";
$titre_comp="";

//if (empty($_POST)) {

    /*
     *  Il n'y a pas de donnes POST, on prepare les champs du formulaire
     * en fonction du type d'utilisateur
     * client ou freelance de facon non exclusive, un utilisateur peut appartenir aux deux categories
    */
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
        <li><a href="/websphereProject/src/?p=dashboardC">Dashboard client</a></li>
        ';

        $buttonrechercheC = '
        <div class="choix">
                    <h2>Chercher un professionnel pour votre projet web</h2>
                    <a href="/websphereProject/src/?p=dashboardC">
                        <div class="buttonAccueil">
                             <p>Créer votre projet et trouver un Freelance</p> 
                        </div>
                    </a>
                </div>';
        error_log("Model home.php On a renseigne buttonrechercheC : $buttonrechercheC");
    }

if (isset($_SESSION['Freelance'])) {
    $buttonrechercheF = '
    <div class="choix">
				<h2>Vous êtes Freelance ?</h2>
				<a href="/websphereProject/src/?p=dashboardF">
					<div class="buttonAccueil">
				<p>Mettez à jour vos compétences</p>
					</div>
					</a>
			</div>
';

    $menuclientorfreelance .='

        <li><a href="/websphereProject/src/?p=dashboardF">Dashboard freelance</a></li>
        ';

}