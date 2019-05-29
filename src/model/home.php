<?php
error_log("model home.php : ENTREE");
$formulaire_client = "";
$formulaire_creation_projet = "";
$menuclientorfreelance="";

if (empty($_POST)) {


    /*
     *  Il n'y a pas de donnes POST, on prepare les champs du formulaire
     * en fonction du type d'utilisateur
     * client ou freelance de facon non exclusive, un utilisateur peut appartenir aux deux categories
    */
    if (isset($_SESSION['Client'])) {
        error_log("Model home.php POST EMPTY On est Client");
        $resultskill = new Skill ($bdd);
        $skill = $resultskill->getSkills();
        $listcomp = '<ul>';

        foreach ($skill as $element) {
            $listcomp .= '
        
                <li value=' . $element['id_skill'] . ' />' . $element['name'] . '<br>
               ';
        }
        $listcomp .= '</ul>';


        $menuclientorfreelance .='

        <li><a href="/websphereProject/src/?p=dashboardC">Dashboard client</a></li>
        ';

$buttonrechercheC="";
        $buttonrechercheC='
        <div class="choix">
                    <h2>Chercher un professionnel pour votre projet web</h2>
                    <button class="buttonAccueil">
                        <a href="/websphereProject/src/?p=lesfreelances"> Trouver un Freelance </a>
                    </button>
                </div>';



        /*
         * Formulaire creation projet
         */

        $formulaire_creation_projet = '
            <div class="project">
                <form role="form" method="POST">
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
                            <input class="inputcss" type="password" name="price" class="form-control" id="price" placeholder=""
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="enterprise_name">Contenu</label>
                            <input class="inputcss" type="text" name="content" class="form-control" id="content"
                                   placeholder="Nom entreprise" value="" required>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Poster le projet</button>
                    </div>
                </form>
            </div>
        ';
    }


    // new project
} else {
    /*
        *  Il a des donnes POST envoyees par le formulaire precedemment affiche
         * On recupere les champs du formulaire pour creer l'objet Project
         * et appeler la methode createProject qui rneverra par une fonction header
         * sur la page ...
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

    }

    error_log("model home.php : instanciation objet Project ");
    $project = new Project($bdd);
    error_log("model home.php : appel methode createProject de Project ");
    $result = $project->createProject($crP_title, $crP_content, $crP_start_date, $crP_end_date, $crP_price);
    error_log("model home.php : Sortie de methode createProject de Project ");
    error_log("model create.php : SORTIE CONNEXION APRES CREATION PROJET");
    //$msg = "Tous les champs ne sont pas remplis";
}


if (isset($_SESSION['Freelance'])) {
    $buttonrechercheF = '
    <div class="choix">
				<h2>Vous êtes Freelance ?</h2>
					<button class="buttonAccueil">
				<a href="/websphereProject/src/?p=project">Chercher un projet</a>
					</button>
			</div>
';

    $menuclientorfreelance .='

        <li><a href="/websphereProject/src/?p=dashboardF">Dashboard freelance</a></li>
        ';

}


/*
$resultskill = new Skill ($bdd);
$skill = $resultskill->getSkills();
$formulaire_freelance = "";
foreach ($skill as $element) {
    $formulaire_freelance .= '
             
                <input type="checkbox" name="user_skill[]" value=' . $element['id_skill'] . ' />' . $element['name'] . '<br>
                ';
}

if (!empty($_POST['user_skill'])) {
    $skills = new Skill($bdd);
    $user = $_SESSION['id'];
    $skill = $_POST['id_skill'];
    $result = $skills->insertSkills($user, $skill);
}
*/


















/* if(!empty($_POST) AND isset($_POST['delete'])){
    $article = new Article($bdd);
    $article->deleteArticle($_POST['delete']);
}














$req = $bdd->prepare('SELECT article.id, title, content, user.nom, user.prenom, article_status.type
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
$result=$req->fetchAll();
//echo '<pre>';
//print_r($result);
//echo '</pre>';
$row="";
$start=0;
$lenght=40;

//for ($i=0; $i < count($result); $i++) {
foreach($result as $element){
    $row .= '
    <tr>
        <td>'.$element['id'].'</td>
        <td>'.$element['title'].'</td>
        <td>'.substr($element['content'], $start, $lenght).'...</td>
        <td>'.$element['nom']." ".$element['prenom'].'</td>
        <td>'.$element['type'].'</td>
        <td><a href="?p=editor&id='.$element['id'].'"><i class="fa fa-edit" ></i></a></td>
        <td><i class="fa fa-trash delete" type="button" data-id="'.$element['id'].'" data-type="suppr" data-toggle="modal" data-target="#modaldelete"></i></td>
    </tr>
    ';
}*/

