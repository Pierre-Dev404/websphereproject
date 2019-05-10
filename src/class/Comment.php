<?php
class Comment{
 	private $_id_comment;
    private $_id_auteur;
    private $_id_article;
    private $_date;
   	private $_content;
   	private $_bdd;

   	function __construct($bdd, $idCommentaire = NULL){
   		$this->_bdd=$bdd;
   		if($idCommentaire !== NULL){
   			$data = $this->getComment($idCommentaire);
   			$this->_id_comment=$data['id_comment'];
   			$this->_id_auteur=$data['id_auteur'];
   			$this->_date=$data['date'];
   			$this->_content=$data['content'];   			
   		}
   	}

   	function createComment($idAuteur, $idArticle, $content){
        $req = $this->_bdd->prepare('INSERT INTO 
		comment(content, authorized, date, id_user, id_article) 
		VALUES(:content, :authorized, NOW(), :id_user, :id_article)');
		$req->bindParam(':content', $content);
		$req->bindParam(':authorized', 0);	
		$req->bindParam(':id_user', $idAuteur);
        $req->bindParam(':id_article', $idArticle);							
        $result = $req->execute();		
   		return $result;			   
   	}

   	function getComment($idComment){
        $req = $this->_bdd->prepare('SELECT id, content, authorized, date, id_user, id_article FROM comment WHERE id = :idComment');
        $req->bindParam(':idComment', $idComment);
        $req->execute();
        $result = $req->fetch();   		
   		return $result;	
   	}

   	// function setComment($idComment,$content){
	// 	//update
   	// 	return $this->_comment;
   	// }

   	// function delete($idComment){
   	// 	return $this->_comment;
   	// }

   	// function setAuthorized($idComment,$bool){
	// 	// si 1 on autorise, si 0 on n'autorise pas
   	// 	return $this->_comment;
   	// }

   	function listAll(){
		$req = $this->_bdd->prepare('SELECT comment.id, article.title, comment.content FROM article INNER JOIN comment ON article.id = comment.id_article');  
		$req->execute();
		$result=$req->fetchAll();
   		return $result;
   	}

   	// function listAllByArticle($idArticle){
   	// 	return $this->_comment;
   	// }

}					
//$accesbdd, $idCOmmentaire (facultatif)	
//$monCommentaire = new Comment($bdd,1);
// $monCommentaire = new Comment($bdd);
// $result= $monCommentaire->getComment(1);
// var_dump($result);
