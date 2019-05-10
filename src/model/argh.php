<?php
#$valeurs = ['nom'=>'Glandu', 'prenom'=>'Raymond', 'mail' => 'rglandu@gmail.ru', 'password' => 'glop'];
#$requete = 'INSERT INTO user (nom, prenom, mail, password)
#                 VALUES (:nom, :prenom, :mail)';
#$requete_preparee = $bdd->prepare($requete);

#$requete_preparee->execute($valeurs);


$name="Glandu";
$firstname="Raymond";
$mail="rglandu@gmail.ru";
$password="monpassword";
$avatar="none";
$civilite="1";
$status="1";


            $nameargh="argh" ;
            $firstnameargh="camarche" ;
            $mailargh="glopglop@gegemail.com" ;
            $req=$bdd->prepare("INSERT INTO user (nom, prenom, mail, password, avatar, id_civilite, id_user_status)
                                                VALUES (:nom, :prenom, :mail, :password, :avatar, :idcivilite, :idstatus)");

            if ($req->execute(array(
                'nom' => $name,
                'prenom' => $firstname,
                'mail' => $mail,
                'password' => $password,
                'avatar' => $avatar,
                'idcivilite' => $civilite,
                'idstatus' => $status
            ))) {
                echo "Succes";
                } else
                {
                echo "une erreur est survenue lors de l'insertion";
                    print_r($req->errorInfo());
                };
            
            $mareponse=$bdd->query('select * from user');
            while ($donnees = $mareponse->fetch())
            {
                echo '<p>' . $donnees['nom'] . '</p>' ;
            }


            $req=$bdd->prepare("select * from user");

            $req->execute();
            while ($donnees = $req->fetch())
            {
                echo '<p>' . $donnees['nom'] . '</p>' ;
            }
            $req->closeCursor();


 


?>

