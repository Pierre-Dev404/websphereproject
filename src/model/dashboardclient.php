<?php





error_log("model dashboardclient.php : ENTREE");

// message de la pop_up comment ca marche:
$message_pop_up='<div id="pop-up" class="modal">
            <h3>Client :</h3>
            <p> Vous êtes client et souhaitez faire réaliser un projet web ?<br>
                Quelques étapes à suivre : <br>
                1- Allez sur votre dashboard client, créez un projet en remplissant les champs indiqués et postez le.<br>
                2- Après création, cliquez sur \"gérer votre projet\" depuis cette page, vous pourrez choisir un ou plusieurs freelances selon <br>
                les compétences souhaitées à la réalisation. <br>
                3- Attendez qu\'un freelance accepte votre projet pour pouvoir vous mettre en relation.
            </p>

            <h3>Freelance :</h3>
            <p> Si vous êtes freelance, consultez votre dashboard régulièrement pour voir si vous avez des projets proposés <br>
                afin de les accepter.
                Le cas échéant, vous pourrez accéder aux coordonnées de votre interlocuteur.
            </p>
            <a href="#" rel="modal:close">Close</a>
        </div>';

if (isset($_SESSION['Client'])) {
    error_log("model dashboardclient.php : Session client is set");
    $menuclientorfreelance = '

        <a href="/websphereProject/src/?p=dashboardC">Dashboard client</a>
    
       ';

    // Formulaire de création de projet
    // On (re)affiche le formulaire de creation de projet dans tous les cas
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


    // On récupère tous les projets créés par l'utilisateur qui ont le statut 1,2 ou 3 et quand le type d'utilisateur
    // est client (2).
    $myproject= new Project($bdd);
    $id=$_SESSION['id'];
    error_log("model dashboardclient.php : Test si des projets sont crees = getAllProjectsByIdUserAndStatus($id, '2', '1,2,3')");
    $result = $myproject->getAllProjectsByIdUserAndStatus($id, '2', '1,2,3');
    $mesprojets_default_message="<div class='projet'> <p>Vous n'avez encore créé aucun projet</p>";
    $mesprojets=$mesprojets_default_message;


    $getinfo = new User ($bdd);

    // On les affiche :
    foreach($result as $element) {
        if ($mesprojets == $mesprojets_default_message) {
            $mesprojets = '<div class="projet">';
        }

        $resultcontactinfo = $getinfo->getConctactInfoFromFreelance($element['id_project']);
        $mesprojets .= '
<div class="allproject">
       
        <p> Titre: ' . $element['title'] . '</p>
        <p> Date de début: ' . $element['start_date'] . '</p>
        <p>Date de fin: ' . $element['end_date'] . '</p>
        <p>Prix: ' . (float)$element['price'] . '</p> <br>';

        // Si le projet est différent du statut, donc accepté par un freelance,
        // On envoi les informations du freelance lié à se projet.
        if($element['idProjectStatus'] != 1){
        $mesprojets .= '
        <p> Contacter le freelance </p>
        <p>Mail: ' . $resultcontactinfo['mail'] . '</p>
        <p>Téléphone: ' . $resultcontactinfo['phone'] . '</p>';
        }
        $mesprojets .= '
        <p>Avancement: ' . $element['status_name'] . ' / ' . $element['idProjectStatus'] . '</p>
        
      <!-- Ici on récupère les information du projet selectionné,
           qui seront envoyés dans la page de gestion de projet 
           qu on récupère grace au name, --->
        <form role="form" method="post" action="?p=gestionprojetC">
            <input  type="hidden" name="nm_id_project" value="' . $element['id_project'] . '">
            <input  type="hidden" name="nm_title" value="' . $element['title'] . '">
            <input  type="hidden" name="nm_start_date" value="' . $element['start_date'] . '">
            <input  type="hidden" name="nm_end_date" value="' . $element['end_date'] . '">
            <input  type="hidden" name="nm_price" value="' . $element['price'] . '">
            <input  type="hidden" name="nm_content" value="' . $element['content'] . '">
            <input  type="hidden" name="nm_status_name" value="' . $element['status_name'] . '">
            <input  type="hidden" name="nm_idProjectStatus" value="' . $element['idProjectStatus'] . '">
            <button type="submit" class="btn btn-primary">Gérer votre projet</button>  
        </form>
</div>
        ';
    }
    $mesprojets.='</div>';
}


// Affichage des projets terminés



error_log("model dashboardclient.php : Test si des projets sont termines = getAllProjectsByIdUserAndStatus($id, '2', '4')");
$result_projet_terminé = $myproject->getAllProjectsByIdUserAndStatus($id, '2', '4');

$mesprojets_default_message_terminate = "<p>Vous n'avez aucun projet terminé</p>";
error_log("model dashboardclient.php : affectation variable mesprojetstermines");
$mesprojetstermines = $mesprojets_default_message_terminate;
error_log("model dashboardclient.php :  variable mesprojetstermines affectée initial : $mesprojetstermines");


//$mesprojetstermines .= '<div class="projet">';
foreach ($result_projet_terminé as $element) {
    if ($mesprojetstermines == $mesprojets_default_message_terminate) {
        error_log("model dashboardclient.php :  variable mesprojetstermines est a la valeur par défaut, on la vide");
        $mesprojetstermines ='<div class="projet">';
    }
    $mesprojetstermines .= '
<div class="allproject">
       
        <p> Titre: ' . $element['title'] . '</p>
        <p> Date de début: ' . $element['start_date'] . '</p>
        <p>Date de fin: ' . $element['end_date'] . '</p>
        <p>Prix :' . (float)$element['price'] . '</p>
        <p>Avancement: ' . $element['status_name'] . ' / ' . $element['idProjectStatus'] . '</p>
</div>
        ';
    error_log("model dashboardclient.php :  ariable mesprojetstermines valeur en cours de construction : $mesprojetstermines");

    }
$mesprojetstermines.="</div> <!-- coucou fin div projet -->";
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

        $project = new Project($bdd);
        $result = $project->createProject($crP_title, $crP_start_date, $crP_end_date, $crP_price, $crP_content);

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

        <a href="/websphereProject/src/?p=dashboardF">Dashboard freelance</a>
        ';

}