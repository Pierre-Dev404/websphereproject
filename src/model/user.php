<?php

    // $req = $bdd->prepare('SELECT id, nom, prenom, mail FROM user');
    // $req->execute();
    // $result=$req->fetchAll();
    $user = new User($bdd);
    $list = $user->getAllUser();

    $row="";

    //for ($i=0; $i < count($result); $i++) { 
    foreach($list as $element){
        $row .= '
        <tr>
        <td>'.$element['prenom'].'</td>
        <td>'.$element['nom'].'</td>
        <td>'.$element['mail'].'</td>
        <td>'.$element['id'].'</td>
        </tr>
        ';
    }

?>