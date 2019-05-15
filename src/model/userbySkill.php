<?php

if(isset($_POST)) {
    $resultskill = new Skill ($bdd);
    $skill = $resultskill->getSkills();
    $listskill = "";
        foreach ($skill as $element) {
            $listskill .= '
                <input class="user" type="checkbox" name="skill[]" value='. $element['id_skill'] . ' />' . $element['name'] . '<br>
                ';
        }

    }

    $type = new User ($bdd);
    $result = $type->UserBySkills();
    $usersbyskill = "";
    foreach ($result as $freelance) {
        $usersbyskill .= '
            <p>' . $freelance['name'] . '</p>
            <p>' . $freelance[''] . '</p>
            <p>' . $freelance['firstname'] . '</p>
            <p>' . $freelance['enterprise_name'] . '</p>
            <p>' . $freelance['city'] . '</p>
            
            ';

}