<?php



// Les traitements qui suivent sont executes qu'il y ait des donnees POST ou non !!

error_log("model dashboardclient.php : ENTREE");
// Formulaire de création de projet
// On (re)affiche le formulaire de creation de projet dans tous les cas

$menuclientorfreelance="<ul>";
if (isset($_SESSION['Client'])) {
    error_log("model dashboardclient.php : Session client is set");
    $menuclientorfreelance .= '

        <li><a href="/websphereProject/src/?p=dashboardC">Dashboard client</a></li>
    
       ';

    $formulaire_creation_projet = '
            <div class="container">
                <form id="contact" action="" method="post">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="nom">Titre</label>
                            <input class="inputcss" type="text" name="title" class="form-control" id="title" required>
                        </div>
                        <div class="form-group">
                            <label for="prenom">Date de debut</label>
                            <input class="inputcss" type="date" name="start_date" class="form-control" id="date_s" required>
                        </div>
                        <div class="form-group">
                            <label for="mail">Date de fin</label>
                            <input class="inputcss" type="date" name="end_date" class="form-control" id="date_e" required>
                        </div>
                        <div class="form-group">
                            <label>Prix</label>
                            <input class="inputcss" type="number" name="price" class="form-control" id="price" required>
                        </div>
                        <div class="form-group">
                            <label for="enterprise_name">Contenu</label>
                            <textarea class="inputcss" name="content" class="form-control" id="content" required > </textarea>
                            </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Poster le projet</button>
                    </div>
                </form>
            </div>
        ';

    $myproject= new Project($bdd);
    $id=$_SESSION['id'];
    error_log("model dashboardclient.php : Test si des projets sont crees = getAllProjectsByIdUserAndStatus($id, '2', '1,2,3')");
    $result = $myproject->getAllProjectsByIdUserAndStatus($id, '2', '1,2,3');
    $mesprojets_default_message="<p>Vous n'avez encore créé aucun projet</p>";
    $mesprojets=$mesprojets_default_message;


    $getinfo = new User ($bdd);

    foreach($result as $element) {
        if ($mesprojets == $mesprojets_default_message) {
            $mesprojets = '';
        }

        $resultcontactinfo = $getinfo->getConctactInfoFromFreelance($element['id_project']);
        $mesprojets .= '
<div class="allproject">
       
        <p> TITRE ' . $element['title'] . '</p>
        <p> DATE DEBUT ' . $element['start_date'] . '</p>
        <p>DATE FIN ' . $element['end_date'] . '</p>
        <p>PRIX ' . $element['price'] . '</p>
        <p>MAIL ' . $resultcontactinfo['mail'] . '</p>
        <p>PHONE ' . $resultcontactinfo['phone'] . '</p>
        <p>AVANCEMENT ' . $element['status_name'] . ' / ' . $element['idProjectStatus'] . '</p>
        
        <form role="form" method="post" action="?p=gestionprojetC">
            <input  type="hidden" name="nm_id_project" value="' . $element['id_project'] . '">
            <input  type="hidden" name="nm_title" value="' . $element['title'] . '">
            <input  type="hidden" name="nm_start_date" value="' . $element['start_date'] . '">
            <input  type="hidden" name="nm_end_date" value="' . $element['end_date'] . '">
            <input  type="hidden" name="nm_price" value="' . $element['price'] . '">
            <input  type="hidden" name="nm_content" value="' . $element['content'] . '">
            <input  type="hidden" name="nm_status_name" value="' . $element['status_name'] . '">
            <input  type="hidden" name="nm_idProjectStatus" value="' . $element['idProjectStatus'] . '">
            <button type="submit">Gérer votre projet</button>  
        </form>
</div>
        ';



    }
}


// Affichage des projets terminés



error_log("model dashboardclient.php : Test si des projets sont termines = getAllProjectsByIdUserAndStatus($id, '2', '4')");
$result_projet_terminé = $myproject->getAllProjectsByIdUserAndStatus($id, '2', '4');

$mesprojets_default_message_terminate = "<p>Vous n'avez aucun projet terminé</p>";
error_log("model dashboardclient.php : affectation variable mesprojetstermines");
$mesprojetstermines = $mesprojets_default_message_terminate;
error_log("model dashboardclient.php :  variable mesprojetstermines affectée initial : $mesprojetstermines");

foreach ($result_projet_terminé as $element) {
    if ($mesprojetstermines == $mesprojets_default_message_terminate) {
        error_log("model dashboardclient.php :  variable mesprojetstermines est a la valeur par défaut, on la vide");
        $mesprojetstermines = '';
    }
    $mesprojetstermines .= '
<div class="allproject">
       
        <p> TITRE ' . $element['title'] . '</p>
        <p> DATE DEBUT ' . $element['start_date'] . '</p>
        <p>DATE FIN ' . $element['end_date'] . '</p>
        <p>PRIX ' . $element['price'] . '</p>
        <p>AVANCEMENT ' . $element['status_name'] . ' / ' . $element['idProjectStatus'] . '</p>
</div>
        ';
    error_log("model dashboardclient.php :  ariable mesprojetstermines valeur en cours de construction : $mesprojetstermines");

    }
error_log("model dashboardclient.php :  variable mesprojetstermines affectée valeur finale : $mesprojetstermines");



if(!empty($_POST)) {
    error_log("model dashboardclient.php : POST NON VIDE");

    /*
        *  Il a des donnes POST envoyees par un formulaire
         *  Ces donnees POST proviennent soit du formulaire de creation de projet (LEFT)
         * soit d'un formulaire Gerer Projet genere par la lecture des projets utilisateur
         * On se sert des champs envoyes pour tester la nature des donnees recues
         * On recupere les champs du formulaire pour creer l'objet Project
         * et appeler la methode createProject ...
         *
        */
    // $cr_title,$cr_content,$cr_start_date,$cr_end_date,$cr_price,$cr_id_project_status
    if (!empty($_POST['title'])
        AND !empty($_POST['start_date'])
        AND !empty($_POST['end_date'])
        AND !empty($_POST['price'])
        AND !empty($_POST['content'])) {
        $crP_title = $_POST['title'];
        $crP_start_date = $_POST['start_date'];
        $crP_end_date = $_POST['end_date'];
        $crP_price = $_POST['price'];
        $crP_content = $_POST['content'];
        // On arrive du formulaire de creation de projet

        error_log("model dashboardclient.php : instanciation objet Project ");
        $project = new Project($bdd);
        error_log("model dashboardclient.php : appel methode createProject de Project = createProject($crP_title, $crP_start_date, $crP_end_date, $crP_price, $crP_content)");
        $result = $project->createProject($crP_title, $crP_start_date, $crP_end_date, $crP_price, $crP_content);
        error_log("model dashboardclient.php : Sortie de methode createProject de Project ");
        error_log("model dashboardclient.php : SORTIE CONNEXION APRES CREATION PROJET");

    } else {
        error_log("model dashboardclient.php : TOUS LES CHAMPS N'ONT PAS ETE SAISIS");
        if (!empty($_POST['nm_id_project'])) {
            // On gere le projet sur lequel on a clique
            // rien a faire, on est parti sur la page de gestion du projet



        }

    }

    //

}

    if (isset($_SESSION['Freelance'])) {
        $menuclientorfreelance .= '

        <li><a href="/websphereProject/src/?p=dashboardF">Dashboard freelance</a></li>
        ';

}
    $menuclientorfreelance.="</ul>";