<?php
class TypeUser {
    private $_bdd;



   /*
    Si on oublie de passer un argument à une méthode et que celui-ci est attendu, 
    PHP va renvoyer une erreur
    Si cet argument est facultatif on lui attribue une valeur par défaut dans la méthode
    Exemple :
        function maMethode($mavariable = "LaPiscine")
        Par défaut $mavariable vaut "LaPiscine"
    Si lorsqu'on appelle la méthode maMethode on lui passe une valeur
    Exemple :
        $result= $var->maMethode("Bordeaux")
        $mavariable dans la méthode vaut "Bordeaux"
        $result= $var->maMethode()
        $mavariable dans la méthode vaut "LaPiscine"        
    */



    function __construct($bdd)
    {
        $this->_bdd = $bdd;

    }

    function getAllTypes()
    {
        $req = $this->_bdd->prepare("SELECT *
                                    FROM type");
        $req->execute();
        $result = $req->fetchAll();
       // var_dump($result);
        return $result ;
    }



}


?>