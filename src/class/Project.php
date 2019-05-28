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

    function getProjectProposeToFreelance($gpt_id_user, $gtp_id_project_status){

        $req = $this->_bdd->prepare('SELECT p.id_project, p.title, p.content, p.price FROM project p 
                                    JOIN work w ON w.id_project = p.id_project
                                    WHERE w.id_user=:gtp_id_user AND w.id_type = 1 AND p.id_project_status =:gtp_id_project_status');

        $req->bindParam(':gtp_id_user', $gpt_id_user);
        $req->bindParam(':gtp_id_project_status', $gtp_id_project_status);
        $req->execute();
        return $req->fetchAll();

    }



    function terminateProject($apd_id_user, $apd_id_project)
    {
        error_log("Classe Project,methode acceptProjectandDeleteOtherFl : le id_user est $apd_id_user");
        error_log("Classe Project,methode acceptProjectandDeleteOtherFl : le id_project  est $apd_id_project");
        $DBG_requete = "UPDATE project SET id_project_status = 2 WHERE id_project =$apd_id_project";
        error_log("Classe Project,methode acceptProjectandDeleteOtherFl : premiere requete :  $DBG_requete");
        $req = $this->_bdd->prepare('UPDATE project SET id_project_status = 3 WHERE id_project =:id_project');
        $req->bindParam(':id_project', $apd_id_project);
        $req->execute();
    }




    //Obtention de la liste de tous les projets par id user
    function geAllProjectsByIdUser($gp_id, $gp_id_type){

            $req = $this->_bdd->prepare('SELECT p.id_project , p.title, p.start_date, p.end_date, p.price, p.content, p.id_project_status as idProjectStatus, ps.status as status_name
                                        FROM project p 
                                        JOIN work w ON w.id_project = p.id_project
                                        JOIN project_status ps ON ps.id_project_status = p.id_project_status
                                        WHERE w.id_user = :id_user AND id_type=:id_type_user');
            $req->bindParam(':id_user', $gp_id);
        $req->bindParam(':id_type_user', $gp_id_type);
            $req->execute();
        return $req->fetchAll();
    }

    function acceptProjectandDeleteOtherFl($apd_id_user, $apd_id_project){
        error_log("Classe Project,methode acceptProjectandDeleteOtherFl : le id_user est $apd_id_user");
        error_log("Classe Project,methode acceptProjectandDeleteOtherFl : le id_project  est $apd_id_project");
        $DBG_requete="UPDATE project SET id_project_status = 2 WHERE id_project =$apd_id_project";
        error_log("Classe Project,methode acceptProjectandDeleteOtherFl : premiere requete :  $DBG_requete");
        $req = $this->_bdd->prepare('UPDATE project SET id_project_status = 2 WHERE id_project =:id_project');
        $req->bindParam(':id_project', $apd_id_project);
        $req->execute();

        $DBG_requete="DELETE w
                                        FROM work w 
                                        JOIN project p ON p.id_project = w.id_project
                                        WHERE w.id_user !=$apd_id_user AND w.id_type = 1";
        error_log("Classe Project,methode acceptProjectandDeleteOtherFl : deuxieme requete :  $DBG_requete");
        $req = $this->_bdd->prepare("DELETE w
                                        FROM work w 
                                        JOIN project p ON p.id_project = w.id_project
                                        WHERE w.id_user !=:apd_id_user AND w.id_type = 1");
        $req->bindParam(':apd_id_user', $apd_id_user);
        $req->execute();
        error_log("Classe Project,methode acceptProjectandDeleteOtherFl : SORTIE !!");
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

            $result_insert_project=$req->execute();
            $erreur_insert_project=$req->errorInfo() ;
            error_log("Classe Project,methode createProject : le result_insert_project est $result_insert_project");
            error_log("Classe Project,methode createProject : le erreur_insert_project est $erreur_insert_project");
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

    function assignProject($pp_id_project, $pp_id_freelance) {
        if (isset($_SESSION['Client'])) {
                $id_type_freelance="1";
                //On recupère l'ID du dernier projet inseré
                $req = $this->_bdd->prepare('INSERT INTO work (id_user, id_project, id_type) VALUES (:label_id_user, :label_id_project, :label_id_type)');
                $req->bindParam(':label_id_user', $pp_id_freelance);
                $req->bindParam(':label_id_project', $pp_id_project);
                $req->bindParam(':label_id_type', $id_type_freelance);
                $result = $req->execute();
                $erreur_insert=$req->errorInfo() ;
                if ($erreur_insert['0'] == '23000'){
                    // $disp_error=$erreur_insert['2'];
                    //echo "une erreur est survenue lors de l'insertion $disp_error";
                    return "DUPLICATE_REC";
                } else {
                    return $result;
                }


        } else  {
            // il faut avoir l'attribut Cient pour proposer un projet
                     error_log("Classe Project,methode assignProject : Operation reservee aux profils Client");
                     return ("KO_BAD_PROFILE");
        }
    }


    //Obtention de la liste de tous les projets par id user
    function getAllAssignedFreelancesByIdProject($gaf_id_project){

        $req = $this->_bdd->prepare('SELECT u.id_user FROM users u
                                        JOIN work w ON w.id_user = u.id_user
                                        JOIN project p ON p.id_project = w.id_project
                                        WHERE p.id_project =:id_project AND w.id_type = 1');
        $req->bindParam(':id_project', $gaf_id_project);
        $req->execute();
        $listeAssigned=$req->fetchAll(PDO::FETCH_NUM);
        $retAssigned=array();
        foreach($listeAssigned as $assigned) {
            array_push($retAssigned,$assigned[0]);
        }
        //var_dump($retAssigned);
        return $retAssigned;
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
