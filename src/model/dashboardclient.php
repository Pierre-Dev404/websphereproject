<?php



// Les traitements qui suivent sont executes qu'il y ait des donnees POST ou non !!


// Formulaire de création de projet
// On (re)affiche le formulaire de creation de projet dans tous les cas

$menuclientorfreelance="<ul>";
if (isset($_SESSION['Client'])) {
    $menuclientorfreelance .= '

        <li><a href="/websphereProject/src/?p=dashboardC">Dashboard client</a></li>
    
       ';

    $formulaire_creation_projet = '
            <div class="container">
                <form id="contact" action="" method="post">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="nom">Titre</label>
                            <input class="inputcss" type="text" name="title" class="form-control" id="title" placeholder="Enter nom"
                                   value="" required>
                        </div>
                        <div class="form-group">
                            <label for="prenom">Date de debut</label>
                            <input class="inputcss" type="date" name="start_date" class="form-control" id="date_s"
                                   placeholder="Enter prénom" value="" required>
                        </div>
                        <div class="form-group">
                            <label for="mail">Date de fin</label>
                            <input class="inputcss" type="date" name="end_date" class="form-control" id="date_e"
                                   placeholder="Enter email" value="" required>
                        </div>
                        <div class="form-group">
                            <label>Prix</label>
                            <input class="inputcss" type="text" name="price" class="form-control" id="price" placeholder=""
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="enterprise_name">Contenu</label>
                            <textarea class="inputcss" name="content" class="form-control" id="content"
                                   placeholder="Nom entreprise" value="" required> </textarea>
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
    $result = $myproject->geAllProjectsByIdUser($id);
    $mesprojets="";
    foreach($result as $element){
        $mesprojets .= '
<div class="allproject">
       
        <p> TITRE ' . $element['title'] . '</p>
        <p> DATE DEBUT ' . $element['start_date'] . '</p>
        <p>DATE FIN ' . $element['end_date'] . '</p>
        <p>PRIX ' . $element['price'] . '</p>
        <p>AVANCEMENT ' . $element['status_name'] . '</p>
        
        <form role="form" method="post" action="?p=gestionprojetC">
            <input  type="hidden" name="nm_id_project" value="'. $element['id_project'].'">
            <input  type="hidden" name="nm_title" value="'. $element['title'].'">
            <input  type="hidden" name="nm_start_date" value="'. $element['start_date'].'">
            <input  type="hidden" name="nm_end_date" value="'. $element['end_date'].'">
            <input  type="hidden" name="nm_price" value="'. $element['price'].'">
            <input  type="hidden" name="nm_content" value="'. $element['content'].'">
            <input  type="hidden" name="nm_status_name" value="'. $element['status_name'].'">
            <input  type="text" name="nm_idProjectStatus" value="'. $element['idProjectStatus'].'">
            <button type="submit">Gérer votre projet</button>  
        </form>
</div>
        ';

    }


}


// Affichage des projets créés



if(!empty($_POST)) {

    /*
        *  Il a des donnes POST envoyees par un formulaire
         *  Ces donnees POST proviennent soit du formulaire de cretion de projet (LEFT)
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

        error_log("model home.php : instanciation objet Project ");
        $project = new Project($bdd);
        error_log("model home.php : appel methode createProject de Project ");
        $result = $project->createProject($crP_title, $crP_content, $crP_start_date, $crP_end_date, $crP_price);
        error_log("model home.php : Sortie de methode createProject de Project ");
        error_log("model create.php : SORTIE CONNEXION APRES CREATION PROJET");

    } else {
        if (!empty($_POST['nm_id_project'])) {
            // On gere le projet sur lequel on a clique
            // rien a faire, on est parti sur la page de gestion du projet


        }

    }

    //$msg = "Tous les champs ne sont pas remplis";
}

    if (isset($_SESSION['Freelance'])) {
        $menuclientorfreelance .= '

        <li><a href="/websphereProject/src/?p=dashboardF">Dashboard freelance</a></li>
        ';

}
    $menuclientorfreelance.="</ul>";