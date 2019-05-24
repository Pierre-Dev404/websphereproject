<?php
$resultskill = new Skill ($bdd);
$skill = $resultskill->getSkills();
$formulaire_freelance = "";
foreach ($skill as $element) {
    $formulaire_freelance .= '
             
                <input type="checkbox" name="user_skill[]" value=' . $element['id_skill'] . ' />' . $element['name'] . '<br>
                ';
}

if (!empty($_POST['user_skill'])) {
    $skills = new Skill($bdd);
    $user = $_SESSION['id'];
    $skill = $_POST['id_skill'];
    $result = $skills->insertSkills($user, $skill);
}