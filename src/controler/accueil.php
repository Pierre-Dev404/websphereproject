<?php
include('model/create.php');

echo $twig->render('create.html',
    array('title' => 'Inscription',
        'list' => $rowtype
    )

);