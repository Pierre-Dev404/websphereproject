<?php
include('model/userbySkill.php');

echo $twig->render('userbySkill.html',
    array('title' => 'Les Freelances',
        'list' => $listskill,
        'users' => $usersbyskill
    )

);