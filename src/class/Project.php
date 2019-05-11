<?php

class Project{

    private $_id_project;
    private $_title;
    private $_start_date;
    private $_end_date;
    private $_price;
    private $_content;
    private $_id_project_status;
    private $_bdd;

    function __construct($bdd, $idProject = null){
        $this->_bdd=$bdd;
        if($idProject !== NULL){
            $projet = $this->getProjet($idProject);
            $this->_id_project=$idProject;
            $this->_id_project_status=$projet['id_project_status'];
            $this->_title=$projet['title'];
            $this->_start_date=$projet['start_date'];
            $this->_end_date=$projet['end_date'];
            $this->_price=$projet['price'];
            $this->_content=$projet['content'];

        }
        //la variable $bdd n'est pas accessible depuis l'interieur de la classe vers l'exterieur de la classe
        //donc on passe a l'instanciation de la classe article l'objet BDD
        //la variable bdd recu a l'interieur du _consarticlet n'est pas accessible des autres méthodes
        //donc on initialise la variable bdd en dehors de la méthode consarticlet et a l'interieur de la classe
        //on l'appelle maintenant avec this->_bdd
    }

    //Obtention des infos concernant 1 article en spécifiant sont ID dans la méthode
    function getProject(){

        $req = $this->_bdd->prepare('SELECT * 
                                        FROM project
                                        WHERE id_project_status = 1');
        $req->execute();
        return $req->fetchAll();
    }

    //Obtention de la liste de tous les articles
    function getListArticle(){

        $req = $this->_bdd->prepare('SELECT article.id, title, content, user.nom, user.prenom, article_status.type, coverImage 
                        FROM article 
                        INNER JOIN rel_event_article ON rel_event_article.id_article=article.id 
                        INNER JOIN user ON user.id = rel_event_article.id
                        INNER JOIN article_status ON article_status.id = rel_event_article.id_article_status 
                        AND rel_event_article.id_article_status !=3
                        AND date = (SELECT MAX(date)
                                FROM rel_event_article
                                WHERE rel_event_article.id_article = article.id)
                        ORDER BY article.id');
        $req->execute();
        return $req->fetchAll();
    }

    //creation d'un article
    function createArticle($titre,$content,$imgName,$imgError,$imgSize,$imgTmp,$id_category,$id_user)
    {
        $pathFile="files/articles/";
        $pathImg = $pathFile.$imgName;
        $req = $this->_bdd->prepare('INSERT INTO article (title, content, coverImage) 
                        VALUES (:titre, :content, :coverImage)');
        $req->bindParam (':titre', $titre);
        $req->bindParam (':content', $content);
        $req->bindParam (':coverImage', $pathImg);
        $req->execute();
        //On recupère l'ID du dernier article inseré
        $lastId =$this->_bdd->lastInsertId();
        //On injecte ensuite la catégorie et l'ID de l'article dans la table 'rel_article_category'
        $req = $this->_bdd->prepare('INSERT INTO rel_article_category (id, id_article) VALUES (:categorie,:lastId)');
        $req->execute(array(
            'lastId' => $lastId,
            'categorie' => $id_category
        ));
        //On injecte ensuite l'ID user, l'ID de l'art. le status et une DATE dans la table 'rel_event_article'
        $req = $this->_bdd->prepare('INSERT INTO rel_event_article (id, id_article, id_article_status, date) 
                              VALUES (:user,:lastId,1,NOW())');
        $req->execute(array(
            'user' => $id_user,
            'lastId' => $lastId,
        ));
        $this->saveImgArticle($imgName,$imgError,$imgSize,$imgTmp);
    }
    
    function saveImgArticle($imgName,$imgError,$imgSize,$imgTmp){
        $pathFile="files/articles/";
        if (isset($imgName) AND $imgError == 0)
        {
            // Testons si le fichier n'est pas trop gros
            if ($imgSize <= 3145728)
            {
                // Testons si l'extension est autorisée
                $infosFichier = pathinfo($imgName);
                $extensionsAutorisees = array('jpg', 'jpeg', 'gif', 'png');
                if (in_array($infosFichier['extension'], $extensionsAutorisees))
                {
                    // On peut valider le fichier et le stocker définitivement
                    move_uploaded_file($imgTmp, $pathFile.basename($imgName));
                    echo "L'envoi a bien été effectué !";
                }
                else
                {
                    echo 'extention non-autorisee';
                }
            }
            else
            {
                echo 'image trop grosse';
            }
        }
        elseif (isset($imgName) AND $imgError == UPLOAD_ERR_NO_FILE)
        {
            echo 'fichier inexistant';
        }
        elseif (isset($imgName) AND $imgError == UPLOAD_ERR_PARTIAL)
        {
            echo 'fichier uploadé que partiellement';
        }
        elseif (isset($imgName) AND $imgError == UPLOAD_ERR_INI_SIZE)
        {
            echo 'fichier trop gros, personne n\'est gros';
        }
        elseif (isset($imgName) AND $imgError == UPLOAD_ERR_FORM_SIZE)
        {
            echo 'fichier trop gros';
        }
        elseif (!isset($imgName))
        {
            echo 'pas de variable';
        }
        else
        {
            echo 'probleme a l\'envoi';
        }

        return 'true';        
    }
    //Update d'un article
    function uptadeArticle($titre,$content,$img,$id_article){
        $req = $this->_bdd->prepare('UPDATE article SET title=":title",content=":content",coverImage=":img" WHERE id=:id_article;');
        $req->bindParam (':titre', $titre);
        $req->bindParam (':content', $content);
        $req->bindParam (':coverImage', $img);
        $req->bindParam (':id_article', $id_article);
        $req->execute();
        return 'true';
    }

    //Update d'un article
    function uptadeStatus($id_user,$id_article,$status,$date){
        $req = $this->_bdd->prepare('INSERT INTO rel_event_article(id,id_article, id_article_status, date) 
            VALUES (:id_user , :id_article , :status, NOW())');
        $req->bindParam (':id_user', $id_user);
        $req->bindParam (':id_article', $id_article);
        $req->bindParam (':status', $status);
        $req->execute();
        return 'true';
    }

    //Suppresion d'un article
    function deleteArticle($id_user,$id_article,$status,$date){
        $req = $this->_bdd->prepare('INSERT INTO rel_event_article(id,id_article, id_article_status, date) 
                                      VALUES (:id_user, :id_article,3, NOW())');
        $req->bindParam (':id_user', $id_user);
        $req->bindParam (':id_article', $id_article);
        $req->bindParam (':status', $status);
        $req->execute();
        return 'true';
    }

    //liste des commentaires
    function listComment($id_article){
        $req = $this->_bdd->prepare('SELECT content, authorized, date, id_user FROM comment WHERE id_article = :id_article');
        $req->bindParam (':id_article', $id_article);
        $req->execute();
        return 'true';
    }


}

//TEST//

//$article=new Article($bdd);

//Obtention de la liste de tous les articles
//$test= $article->getListArticle();
/*echo '<pre>';
print_r($test);
echo '</pre>';*/

//Obtention des infos concernant 1 article en spécifiant sont ID dans la méthode
//$test2=$article->getArticle(1);

//creation d'un article
//$test3=$article->createArticle($_POST['titre'],$_POST['content'],$_POST['coverImage']);

//Update d'un article
//$test4=$article->uptadeArticle($_POST['titre'],$_POST['content'],$_POST['coverImage'],$_POST['id_article']);

//Update du status
//$test5=$article->uptadeStatus($_POST['id_user'],$_POST['id_article'],$_POST['status'],$_POST['date']);

//Suppresion d'un article
//$test6=$article->deleteArticle($_POST['id_user'], $_POST['id_article'],$_POST['status'],$_POST['date']);

//liste des commentaires
//$test7=$article->listComment($_POST['id_article']);

//echo '<pre>';
//print_r($result2);
//echo '</pre>';

//$article->createArticle($_POST['titre'],$_POST['content'],$_POST['img']);


//$article=new Article($bdd,2);
