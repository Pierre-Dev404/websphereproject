<?php

    $categorie = new Categorie();
    $result=$categorie->getAll();


    // $req = $bdd->prepare('SELECT id, type FROM category_blog');
    // $req->execute();
    // $result=$req->fetchAll();
    // echo '<pre>';
    // print_r($result);
    // echo '</pre>';
    $row="";

    //for ($i=0; $i < count($result); $i++) { 
    foreach($result as $element){
        $row .= '
        <tr>
        <td>'.$element['id'].'</td>
        <td>'.$element['type'].'</td>
        </tr>
        ';
    }
?>