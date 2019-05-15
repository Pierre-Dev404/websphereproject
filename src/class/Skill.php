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
            $this->_name = $skill['name'];

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


}
