<?php

$resultskill = new Skill ($bdd);
$skill = $resultskill->getSkills();
$listskill = "";
foreach ($skill as $element) {
    $listskill .= '
            
                <input class="test" type="checkbox" name="user_skill[]" value=' . $element['id_skill'] . ' />' . $element['name'] . '<br>
                
                ';

    var_dump($element['id_skill']);
}



    if (!empty($_POST['user_skill'])) {
        $type = new User ($bdd);
        $result = $type->UserBySkills($userskill,$skill);
        $usersbyskill = "";
        $userskill = $_POST['user_skill'];
        $skill=$_POST['id_skill'];
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
        var_dump($usersbyskill);



