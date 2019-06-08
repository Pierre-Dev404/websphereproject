<?php
/**
 * Created by PhpStorm.
 * User: pierremeunier
 * Date: 2019-05-15
 * Time: 16:17
 */

class Skill
{

    private $_bdd;

    function __construct($bdd)
    {
        $this->_bdd = $bdd;

        }


        # récupère tous les types de compétences existants
        # Utilisé  notamment quand il y a besoin de créer les formulaire de recherche de skill, et d'insertion de skill
        # utilisé à l'accueil pour afficher les différentes compétences disponibles
    function getSkills()
    {
        $req = $this->_bdd->prepare("SELECT id_skill, name
                                    FROM skill");
        $req->execute();
        $result = $req->fetchAll();
        return $result;
    }

    # Permet pour un utilisateur Freelance, d'ajouter une compétences
    # L'insert ignore permet de ne pas afficher d'erreur si on insert la même compétences.
    function insertSkills($is_user, $is_skill)
    {
        foreach ($is_skill as $type) {
            error_log("La valeur de type traitee est $type ");
            $req = $this->_bdd->prepare("INSERT IGNORE INTO user_skill (id_skill, id_user)
                                    VALUES (:id_type, :id_user)");

            $req->bindParam(':id_user', $is_user);
            $req->bindParam(':id_type', $type);
            if ($req->execute()) {
                echo "Succes";
            } else {
                echo "une erreur est survenue lors de l'insertion dans userskill";
            }

        }


    }
}