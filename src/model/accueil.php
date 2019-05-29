<?php
error_log( "model accueil.php :ENTREE" );
$resultskill = new Skill ($bdd);
$skill = $resultskill->getSkills();
$listskill_val = "";


    foreach ($skill as $element) {
        $listskill_val .= ' ' . $element['name'] . ',';

        //var_dump($element['id_skill']);
    }

    $listskill_val=substr_replace($listskill_val, '.', -1, 1) ;
$listskill="<p class='test' name='skills'>" . $listskill_val . " </p>";
error_log( "model accueil.php : liste skills : $listskill" );
//<p class="test" type="text" name="skills" value=' . $element['id_skill'] . ' />' . $element['name'] . '<br> </p>
error_log( "model accueil.php :ENTREE" );


