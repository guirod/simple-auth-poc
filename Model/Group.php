<?php

namespace Model;

use Model\ICrud;
use Model\Connexion;
use PDOException;

class Group implements ICrud
{
    const GROUP_ADMIN = 'Admin';
    const GROUP_MEMBER = 'Member';
    private int $id;
    private string $name;

    // public function __construct(string $name)
    // {
    //     $this->name = $name;
    // }
    public function __construct()
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Group
    {
        $this->id = $id;
        
        return $this;
    }

    /*
     * TODO 
     */
    public static function hydrate(array $properties): Group
    {
        $group = new Group();
        $group->setId($properties['id']);
        $group->setName($properties['name']);

        return $group;
    }

    public function save()
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stt = $conn->prepare("INSERT INTO `groups` (`name`) VALUES (?)");
            $stt->bindParam(1, $this->name);
            $stt->execute();
            $this->id = $conn->lastInsertId();

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function findByName(string $name): ?Group
    {
        $group = null;

        try {
            $conn = Connexion::getInstance()->getConn();
            $stt = $conn->prepare("SELECT * FROM `groups` WHERE `name` = ?");
            $stt->bindParam(1, $name);
            $stt->execute();
            $group = self::hydrate($stt->fetch());

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        
        return $group; 
    }
}