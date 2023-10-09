<?php
include_once('./Model/Connexion.php');
include_once('./Model/Group.php');
class User
{
    private int $id;
    private string $login;
    private string $passwordHash;
    private ?Group $group = null;



    public function __construct()
    {
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getGroup(): Group
    {
        if($this->group === null) {
            $conn = Connexion::getInstance()->getConn();
            $sql = "SELECT * FROM `groups` g
                    INNER JOIN users_groups ug ON g.id = ug.id_group
                    INNER JOIN users u ON u.id = ug.id_user  
                    WHERE u.id = ?";
            
            $stt = $conn->prepare("SELECT * FROM `users` WHERE `login` = ?");
                $stt->bindParam(1, $this->id, PDO::PARAM_INT);
                $stt->execute();

                $dbhash = null;
                $userArray = [];
                if ($stt->rowCount() === 1) {
                    $userArray = $stt->fetch();
                    $dbhash = $userArray['password_hash'];
                }
            // TODO Requete sql
            // $this->group = ...
        }

        return $this->group;
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
}
