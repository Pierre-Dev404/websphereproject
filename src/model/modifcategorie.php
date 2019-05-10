<?php
$req = $bdd->prepare('SELECT id, type FROM categorie_blog');
$req->bindParam(':id',$idCategorie);
$req->execute();
$result=$req->fetch();
// echo '<pre>';
// print_r($result);
// echo '</pre>';

// $title = $result['title'];
// $content = $result['content'];
// $coverImage = $result['coverImage'];