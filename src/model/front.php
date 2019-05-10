<?php
/*
$req = $bdd->prepare('SELECT title, content, coverImage, article.id, user.nom, user.prenom, rel_event_article.date
FROM article
INNER JOIN rel_event_article ON rel_event_article.id_article = article.id
INNER JOIN user ON user.id = rel_event_article.id
INNER JOIN article_status ON article_status.id = rel_event_article.id_article_status
WHERE article_status.id = 2');
$req->execute();
$result = $req->fetchAll();



// instanciation de la class Article et 
//récupération du résultat de l'objet front()
$objarticle=new Article($bdd);
$result= $objarticle->getListArticle();






 
foreach ($result as $element) {
  $article .= '
  <article>
    <header>
      <span class="date">'.$element['date'].'</span>
      <h2><a href="#">'.$element['title'].'</a></h2>
    </header>
    <a href="#" class="image fit"><img src='.$element['coverImage'].'></img></a>
    <p>'.$element['content'].'</p>
    <ul class="actions special">
      <li><a href="#" class="button">Full Story</a></li>
    </ul>
    </header>
  </article>';
}
*/


?>
