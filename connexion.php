<?php
include_once("./Model/Connexion.php");
include_once("./Model/User.php");

session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>

<body>
    <?php
    if (isset($_SESSION['user'])) {
        $user2 = unserialize(base64_decode($_SESSION['user']));
        ?>
        
        <h3>Vous êtes déjà connecté</h3>

        <p><a href="logout.php">Se déconnecter</a></p>
        <?php
    } else {
        ?>

        <form action="connexion.php" method="post">
            <div>
                <label for="user_email">Email</label>
                <input type="email" name="email" id="user_email">
            </div>
            <div>
                <label for="user_password">Password</label>
                <input type="password" name="password" id="user_password">
            </div>
            <input type="submit" value="Se connecter">
        </form>

        <?php
        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            $conn = Connexion::getInstance()->getConn();

            /*
             * Création du hash du password qui sera sauvegardé en BDD. On ne sauvegarde jamais les password en clair
             * La gestion des hash est facilitée en PHP qui fourni des fonctions clé en main.  
             * Utilisation des fonctions intégrées à PHP : 
             *       password_hash() pour la création du hash (https://www.php.net/manual/fr/function.password-hash.php). 
             *                       Pour sa simplicité de mise en oeuvre et sa robustesse, il est recommandé d'utiliser l'algo bcrypt
             *       password_verify() pour comparer un hash (sauvegardé en BDD) avec un MDP entré par l'utilisateur (https://www.php.net/manual/fr/function.password-verify.php)
             */

            $email = $_POST['email'];
            $userPassword = $_POST['password'];

            try {
                $stt = $conn->prepare("SELECT * FROM `users` WHERE `login` = ?");
                $stt->bindParam(1, $email);
                $stt->execute();

                $dbhash = null;
                $userArray = [];
                if ($stt->rowCount() === 1) {
                    $userArray = $stt->fetch();
                    $dbhash = $userArray['password_hash'];
                }

                if (password_verify($userPassword, $dbhash)) {
                    // $_SESSION['authentified'] = true;
                    $user = User::hydrate($userArray);

                    $_SESSION['user'] = base64_encode(serialize($user));

                    $user2 = unserialize(base64_decode($_SESSION['user']));
                    header('location: connexion.php');
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        ?>
        <?php
    }
    ?>
</body>

</html>