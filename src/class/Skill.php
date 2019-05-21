<?php
/**
 * Created by PhpStorm.
 * User: pierremeunier
 * Date: 2019-05-15
 * Time: 16:17
 */

class Skill
{
    private $_id_skill;
    private $_name;

    function __construct($bdd, $idSkill = null)
    {
        $this->_bdd = $bdd;
        if ($idSkill !== NULL) {
            $skill = $this->getSkills($idSkill);
            $this->_id_skill = $idSkill;
            $this->_name = $skill['nom'];

        }
    }

    function getSkills()
    {
        $req = $this->_bdd->prepare("SELECT id_skill, name
                                    FROM skill");
        $req->execute();
        $result = $req->fetchAll();
        return $result;
    }

    function insertSKills()
    {
        $cp_id_user = $_SESSION['id'];
        foreach ($_POST['user_skill'] as $type) {
            error_log("La valeur de type traitee est $type ");
            $req = $this->_bdd->prepare("INSERT INTO user_skill (id_skill, id_user)
                                    VALUES (:id_type, :id_user)");
            if ($req->execute(array(
                'id_type' => $type,
                'id_user' =>  $cp_id_user,
            ))) {
                echo "Succes";
            } else {
                echo "une erreur est survenue lors de l'insertion dans userskill";
                print_r($req->errorInfo());
            }

        }


    }
}