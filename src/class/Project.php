<?php

class Project{

    private $_bdd;

    function __construct($bdd){
        $this->_bdd=$bdd;

        //la variable $bdd n'est pas accessible depuis l'interieur de la classe vers l'exterieur de la classe
        //donc on passe a l'instanciation de la classe article l'objet BDD
        //la variable bdd recu a l'interieur du _consarticlet n'est pas accessible des autres méthodes
        //donc on initialise la variable bdd en dehors de la méthode consarticlet et a l'interieur de la classe
        //on l'appelle maintenant avec this->_bdd
    }

    //Obtention des infos concernant 1 article en spécifiant sont ID dans la méthode


    function deleteProject($dp_id_user, $dp_id_project){

        error_log('DELETE FROM work w WHERE w.id_user=:$dp_id_user AND w.id_type = 1 AND w.id_project =:$dp_id_project');
        $req = $this->_bdd->prepare('DELETE FROM work 
                                            WHERE id_user=:dp_id_user 
                                            AND id_type = 1 
                                            AND id_project =:dp_id_project ');

        $req->bindParam(':dp_id_user', $dp_id_user);
        $req->bindParam(':dp_id_project', $dp_id_project);
        $req->execute();
    }


    function getProjectProposeToFreelance($gpt_id_user, $gtp_id_project_status){

        $req = $this->_bdd->prepare('SELECT p.id_project, p.title, p.content, p.price, p.start_date, p.end_date FROM project p 
                                    JOIN work w ON w.id_project = p.id_project
                                    WHERE w.id_user=:gtp_id_user AND w.id_type = 1 AND p.id_project_status =:gtp_id_project_status');

        $req->bindParam(':gtp_id_user', $gpt_id_user);
        $req->bindParam(':gtp_id_project_status', $gtp_id_project_status);
        $req->execute();
        return $req->fetchAll();

    }

    function validateTerminateProject($vtp_id_project){
        $req = $this->_bdd->prepare('UPDATE project SET id_project_status = 4 WHERE id_project =:id_project');
        $req->bindParam(':id_project', $vtp_id_project);
        $req->execute();
    }

    function refuseDelivery($rd_id_project){
        $req = $this->_bdd->prepare('UPDATE project SET id_project_status = 2 WHERE id_project =:id_project');
        $req->bindParam(':id_project', $rd_id_project);
        $req->execute();
    }

    function setToValidateProjectByClient($apd_id_user, $apd_id_project)
    {
        error_log("Classe Project,methode setToValidateProjectByClient : le id_user est $apd_id_user");
        error_log("Classe Project,methode setToValidateProjectByClient : le id_project  est $apd_id_project");
        $DBG_requete = "UPDATE project SET id_project_status = 3 WHERE id_project =$apd_id_project";
        error_log("Classe Project,methode setToValidateProjectByClient : premiere requete :  $DBG_requete");
        $req = $this->_bdd->prepare('UPDATE project SET id_project_status = 3 WHERE id_project =:id_project');
        $req->bindParam(':id_project', $apd_id_project);
        $req->execute();
    }

    //Obtention de la liste de tous les projets par id user
    function getAllProjectsByIdUserAndStatus($gp_id, $gp_id_type , $gp_status){

            /*$req = $this->_bdd->prepare('SELECT p.id_project , p.title, p.start_date, p.end_date, p.price, p.content, p.id_project_status as idProjectStatus, ps.status as status_name
                                        FROM project p 
                                        JOIN work w ON w.id_project = p.id_project
                                        JOIN project_status ps ON ps.id_project_status = p.id_project_status
                                        WHERE w.id_user = :id_user AND id_type=:id_type_user
                                        AND p.id_project_status IN (:status) ');*/
                // A faire avec le bindParam qui ne foncionne pas avec argument  1,2,3
            $requete_project_by_status="SELECT p.id_project , p.title, p.start_date, p.end_date, p.price, p.content, p.id_project_status as idProjectStatus, ps.status as status_name
                                        FROM project p
                                        JOIN work w ON w.id_project = p.id_project
                                        JOIN project_status ps ON ps.id_project_status = p.id_project_status
                                        WHERE w.id_user = $gp_id AND id_type=$gp_id_type
                                        AND p.id_project_status IN ($gp_status)" ;
            error_log("Classe Project,methode getAllProjectsByIdUserAndStatus : LA requete :  $requete_project_by_status");
                            $resultat_project_status=$this->_bdd->query($requete_project_by_status);
                            return $resultat_project_status;
    }

    function acceptProjectandDeleteOtherFl($apd_id_user, $apd_id_project){
        error_log("Classe Project,methode acceptProjectandDeleteOtherFl : le id_user est $apd_id_user");
        error_log("Classe Project,methode acceptProjectandDeleteOtherFl : le id_project  est $apd_id_project");
        $req = $this->_bdd->prepare('UPDATE project SET id_project_status = 2 WHERE id_project =:id_project');
        $req->bindParam(':id_project', $apd_id_project);
        $req->execute();

        $req = $this->_bdd->prepare("DELETE w
                                        FROM work w 
                                        JOIN project p ON p.id_project = w.id_project
                                        WHERE w.id_user !=:apd_id_user AND w.id_type = 1");
        $req->bindParam(':apd_id_user', $apd_id_user);
        $req->execute();
        error_log("Classe Project,methode acceptProjectandDeleteOtherFl : SORTIE !!");
    }

    //creation d'un projet
    function createProject($cr_title,$cr_start_date,$cr_end_date,$cr_price,$cr_content)
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
            $erreur_insert_project=$req->errorInfo() ;
            $erreur_insert_project_code=$erreur_insert_project['0'] ;
            //On recupère l'ID du dernier projet inseré
            $lastId = $this->_bdd->lastInsertId();
            $ID_TYPE=$_SESSION['Client'];
            error_log("Classe Project,methode createProject : le id_user est $cp_id_user");
            error_log("Classe Project,methode createProject : le lastId recupere de insertion dans project est $lastId");
            error_log("Classe Project,methode createProject : le id_type  est $ID_TYPE");

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

}

