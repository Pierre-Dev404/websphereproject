<?php

    // $req = $bdd->prepare('SELECT id, type FROM category_blog');
    // $req->execute();
    // $result=$req->fetchAll();
    $categorie = new Categorie($bdd);
    $article = new Article($bdd);
    
    $list = $categorie->getAll();

    $options="";

    foreach ($list as $elements) {
        $options .= '<option value="'.$elements['id'].'">'.$elements['type'].'</option>';
    }

    if(!empty($_POST)) {

        $coverImage = "monimgae.jpg";
        $art_status = 2; //brouillon par défaut
        
        $article->createArticle($_POST['title'],$_POST['content'],$coverImage,$_POST['categorie'],$_SESSION['id']);
        header("location: admin.php?p=article");

        // $req = $this->_bdd->prepare('INSERT INTO article (title, content, coverImage) VALUES (:title,:content,:coverImage)');
        // $req->execute(array(
        // ':title' => $title,
        // ':content' => $content,
        // ':coverImage' => $coverImage
        // ));
        // $lastId = $this->_bdd->lastInsertId();
        // $req = $this->_bdd->prepare('INSERT INTO rel_article_category (id, id_article) VALUES (:categorie,:lastId)');
        // $req->execute(array(
        // 'lastId' => $lastId,
        // 'categorie' => $categorie
        // ));
    }

?>