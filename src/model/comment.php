<?php
    $comment = new Comment($bdd);
    $result= $comment->listAll();
    // echo '<pre>';
    // print_r($result);
    // echo '</pre>';
    $row="";
    $trTable ="
    <tr>
    <th>#</th>
    <th>Titre de l'article</th>
    <th>Commentaire</th>
    </tr>";
    //for ($i=0; $i < count($result); $i++) { 
    foreach($result as $element){
        $row .= '
        <tr>
        <td>'.$element['id'].'</td>
        <td>'.$element['title'].'</td>
        <td>'.substr($element['content'], 0, 47)."...".'</td>
        </tr>
        ';
    }

?>