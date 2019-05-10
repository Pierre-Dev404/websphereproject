<?php
/*
$mailTmp="";
$nomTmp="";
$prenomTmp="";
$msg="";
*/

if(!empty($_POST)){
        if(!empty($_POST['nom'])
        AND !empty($_POST['prenom'])
        AND !empty($_POST['mail'])
        AND !empty($_POST['password'])
        AND !empty($_POST['enterprise_name'])
        AND !empty($_POST['siret'])
        AND !empty($_POST['city'])
        AND !empty($_POST['phone'])
        AND !empty($_POST['iban'])){
        $name = $_POST['nom'];
        $firstname = $_POST['prenom'];
        $mail = $_POST['mail'];
        $password = $_POST['password'];
        $enterprise_name = $_POST['enterprise_name'];
        $siret = $_POST['siret'];
        $city = $_POST['city'];
        $phone = $_POST['phone'];
        $iban = $_POST['iban'];
echo "<pre> $iban </pre>" ;

        
    /*
     * if($password != $ConfPassword){
            $mailTmp = $_POST['mail'];
            $nomTmp = $_POST['nom'];
            $prenomTmp = $_POST['prenom'];
            $msg = "Les mots de passe ne correspondent pas";

      }
        else{
            $idcivilite=1;
            $idstatus=2;

            /*$req = $bdd->prepare("INSERT INTO user (nom, prenom, mail, password, id_civilite, id_user_status)
                                    VALUES(:nom, :prenom, :mail, :password, :idcivilite, :idstatus)");
            $req->bindParam(':nom', $nom);
            $req->bindParam(':prenom', $prenom);
            $req->bindParam(':mail', $mail);
            $req->bindParam(':password', $password);
            $req->bindParam(':idcivilite', $idcivilite);
            $req->bindParam(':idstatus', $idstatus);
            $req->execute();*/
            
            $user = new User ($bdd);
            $user->createUser( $name, $firstname, $mail, $password, $enterprise_name, $siret, $city, $iban, $phone);
            // $user = new User ($bdd);
            // $user->createUser('yoyo','asticot','yoastico@gmail.com','papayoyo','img.pjg',3,3);

           }
    }
    else {
        $msg = "Tous les champs ne sont pas remplis";
}
    //penser à vérifier la présence du cnfirm password

    // ensuite ajouter vérif password et password confirm identiques sinon on affiche un message



            
   // print_r($_POST);
       

// else{
//     $message=""
//     $mailtemp = $_POST['mail'];
//     $nomTemp = $_POST['nom'];
//     $prenomTemp = $_POST['prenom'];
// }




        // if(!empty($_POST)){
        //     $req = $bdd->prepare("INSERT INTO user (nom, prenom, mail, password) VALUES($nom, $prenom, $email, $password)");
        //     $req->bindParam(':mail', $mail);
        //     $req->bindParam(':password', $password);
        //     $req->execute();
        // }
    


// On récupère les $_POST et on en fait des variables
 
 


?>

