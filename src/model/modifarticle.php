<?php
$req = $bdd->prepare('SELECT title, content, coverImage FROM article WHERE id = :id');
$req->bindParam(':id',$idArticle);
$req->execute();
$result=$req->fetch();
// echo '<pre>';
// print_r($result);
// echo '</pre>';

$title = $result['title'];
$content = $result['content'];
$coverImage = $result['coverImage'];


