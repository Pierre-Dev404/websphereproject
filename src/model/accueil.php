<?php

$resultskill = new Skill ($bdd);
$skill = $resultskill->getSkills();
$listskill = "";


    foreach ($skill as $element) {
        $listskill .= '
            
                <p class="test" type="text" name="user_skill[]" value=' . $element['id_skill'] . ' />' . $element['name'] . '<br> </p>
                
                ';

        var_dump($element['id_skill']);


}


