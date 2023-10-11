<?php

namespace Model;

class Group
{
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

        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function findByName(string $name): Group
    {
        return new Group();
    }
}