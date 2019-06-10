<?php
error_log( "model accueil.php :ENTREE" );
$resultskill = new Skill ($bdd);
$skill = $resultskill->getSkills();
$listskill_val = "";

// récupéation de tous les skills pour affichage page d'accueil non connecté.
// Un substr_replace pour enlever le dernier tiré et le remplacer par un point.
    foreach ($skill as $element) {
        $listskill_val .= ' ' . $element['name'] . ' - ';

        //var_dump($element['id_skill']);
    }

    $listskill_val=substr_replace($listskill_val, '.', -3, 3) ;
$listskill="<p class='skill_dispo' name='skills'>" . $listskill_val . " </p>";
error_log( "model accueil.php : liste skills : $listskill" );

error_log( "model accueil.php :ENTREE" );


