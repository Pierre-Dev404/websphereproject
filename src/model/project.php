<?php





$project = new Project($bdd);
$result= $project->getProject();
// echo '<pre>';
// print_r($result);
// echo '</pre>';
$row="";
//for ($i=0; $i < count($result); $i++) {
foreach($result as $element) {
    $row .= '


<div class="allproject">
        <p>' . $element['title'] . '</p>
        <p>' . $element['price'] . '</p>
        <p>' . $element['content'] . '</p>
        <button type="submit" name="project[]" value=' . $element['id_project'] . ' class="btn btn-primary">Accepter ce projet</button>
</div>
        ';
}
$user=$_SESSION['id'];
$project=$_POST['id_project'];

    if (!empty($_POST['project'])){
        $project = new Project($bdd);
        $result= $project->acceptProject($user, $project);

}


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

