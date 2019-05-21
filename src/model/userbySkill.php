<?php


    $resultskill = new Skill ($bdd);
    $skill = $resultskill->getSkills();
    $listskill = "";
        foreach ($skill as $element) {
            $listskill .= '
                <input class="user" type="checkbox" name="user_skill[]" value='. $element['id_skill'] . ' />' . $element['name'] . '<br>
                ';


    }

$type = new User ($bdd);
$result = $type->UserBySkills();
$usersbyskill = "";
if (!empty($result)) {
    foreach ($result as $freelance) {
        $usersbyskill .= '
            <div class="free">
                <p>' . $freelance['name'] . '</p>
                <p>' . $freelance['firstname'] . '</p>
                <p>' . $freelance['enterprise_name'] . '</p>
                <p>' . $freelance['city'] . '</p>
        </div>
            ';

    }
}