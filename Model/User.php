<?php

namespace Model;

use Model\ICrud;
use Model\Connexion;
use PDO;
use PDOException;

class User implements ICrud
{
    private int $id;
    private string $login;
    private string $passwordHash;
    private ?array $groups = null;


    public function __construct()
    {
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function addGroup(Group $group): User
    {
        $this->groups[] = $group;

        return $this;
    }

    public function removeGroup(Group $group): User
    {
        return $this;
    }

    public function getGroups(): array
    {
        if($this->groups === null) {
            $conn = Connexion::getInstance()->getConn();
            $sql = "SELECT g.* FROM `groups` g
                    INNER JOIN users_groups ug ON g.id = ug.id_group
                    INNER JOIN users u ON u.id = ug.id_user  
                    WHERE u.id = ?";
            
            $stt = $conn->prepare($sql);
                $stt->bindParam(1, $this->id, PDO::PARAM_INT);
                $stt->execute();

                while ($arrayGroup = $stt->fetch()) {
                    $this->addGroup(Group::hydrate($arrayGroup));
                }
        }

        return $this->groups;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): User
    {
        $this->id = $id;
        
        return $this;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setLogin(string $login): User
    {
        if (!empty($login)) {
            $this->login = $login;
        }
        
        return $this;
    }

    /**
     * BCrypt hash is 60 chars length
     */
    public function setPasswordHash(string $passwordHash): User
    {
        if (strlen($passwordHash) === 60) {
            $this->passwordHash = $passwordHash;
        }
        
        return $this;
    }

    public function save()
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stt = $conn->prepare("INSERT INTO `users` (`login`,password_hash) VALUES (?,?)");
            $stt->bindParam(1, $this->login);
            $stt->bindParam(2,$this->passwordHash);
            $stt->execute();
            $this->id = $conn->lastInsertId();

            // Add the user in the member group & insert a users_groups entry 
            // Get member group : 
            $memberGroup = Group::findByName('Member');
            $userGroup = new UserGroup($this->id, $memberGroup->getId());
            $userGroup->save();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function hydrate(array $properties): User
    {
        $user = new User();
        $user
            ->setId($properties['id'])
            ->setLogin($properties['login'])
            ->setPasswordHash($properties['password_hash']);
        
        return $user;
    }

    public function findById(int $id): User
    {
        return new User();
    }

    public function isAdmin():bool
    {
        foreach($this->getGroups() as $group) {
            if ($group->getName() === Group::GROUP_ADMIN) {
                return true;
            }
        }

        return false;
    }
}
