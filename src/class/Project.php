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
            $projet = $this->getProject($idProject);
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
    function geAllProjectsByIdUser($gp_id){

            $req = $this->_bdd->prepare('SELECT title, price, content
                                        FROM project p 
                                        JOIN work w ON w.id_project = p.id_project
                                        WHERE w.id_user = :id_user');
            $req->bindParam(':id_user', $gp_id);
            $req->execute();
        return $req->fetchAll();
    }

    //creation d'un projet
    function createProject($cr_title,$cr_content,$cr_start_date,$cr_end_date,$cr_price)
    {
        error_log("Classe Project, : Entree methode createProject");
        $status_initial_projet = "1" ; // 1 = Status Non accepte
        if (isset($_SESSION['Client'])) {
            $cp_id_user = $_SESSION['id'];
            $req = $this->_bdd->prepare('INSERT INTO project (title, start_date, end_date, price, content, id_project_status) 
                        VALUES (:title, :start_date, :end_date, :price, :content, :id_project_status)');
            $req->bindParam(':title', $cr_title);
            $req->bindParam(':start_date', $cr_start_date);
            $req->bindParam(':end_date', $cr_end_date);
            $req->bindParam(':price', $cr_price);
            $req->bindParam(':content', $cr_content);
            $req->bindParam(':id_project_status', $status_initial_projet);

            $req->execute();
            //On recupère l'ID du dernier projet inseré
            $lastId = $this->_bdd->lastInsertId();
            $ID_TYPE=$_SESSION['Client'];
            error_log("Classe Project,methode createProject : le id_user est $cp_id_user");
            error_log("Classe Project,methode createProject : le lastId recupere de insertion dans project est $lastId");
            error_log("Classe Project,methode createProject : le id_type  est $ID_TYPE");
            //On injecte ensuite la catégorie et l'ID de l'article dans la table 'rel_article_category'
            error_log("INSERT INTO work (id_user, id_project, id_type) VALUES ($cp_id_user, $lastId , $ID_TYPE)");
            $req = $this->_bdd->prepare('INSERT INTO work (id_user, id_project, id_type) VALUES (:label_id_user, :label_id_project, :label_id_type)');
            $req->bindParam(':label_id_user', $cp_id_user);
            $req->bindParam(':label_id_project', $lastId);
            $req->bindParam(':label_id_type', $ID_TYPE);
            $req->execute();

            header('location: ?p=dashboardC');
        } else  {
            // il faut avoir l'attribut Cient pour pouvoir creer un projet
            echo "Operation reservee aux profils client" ;
        }
    }

    function acceptProject() {
        if (isset($_SESSION['Freelance'])) {
                $acp_id_user = $_SESSION['id'];
                $acceptprojet = "2";
                $req = $this->_bdd->prepare('UPDATE project SET (id_project_status) 
                        VALUES (:id_project_status)');
                $req->bindParam(':id_project_status', $acceptprojet);
                $req->execute();
                //On recupère l'ID du dernier projet inseré
                $lastId = $_POST['id_project'];
                $id_type_user = $_SESSION['Freelance'];
                $req = $this->_bdd->prepare('INSERT INTO work (id_user, id_project, id_type) VALUES (:label_id_user, :label_id_project, :label_id_type)');
                $req->bindParam(':label_id_user', $acp_id_user);
                $req->bindParam(':label_id_project', $lastId);
                $req->bindParam(':label_id_type', $id_type_user);
                $req->execute();

        } else  {
            // il faut avoir l'attribut Cient pour pouvoir creer un projet
            echo "Operation reservee aux profils freelance" ;
        }
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
