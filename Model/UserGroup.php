<?php

namespace Model;


use Model\Connexion;
use PDO;

class UserGroup
{
    private int $idGroup;
    private int $idUser;

    public function getIdGroup()
    {
        return $this->idGroup;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setIdGroup(int $idGroup): UserGroup
    {
        $this->idGroup = $idGroup;

        return $this;
    }

    public function setIdUser(int $idUser): UserGroup
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function save()
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stt = $conn->prepare("INSERT INTO `users_groups` (`id_user`,id_group) VALUES (?,?)");
            $stt->bindParam(1, $this->idUser, PDO::PARAM_INT);
            $stt->bindParam(2, $this->idGroup, PDO::PARAM_INT);
            $stt->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }
}