<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un utilisateur</title>
</head>

<body>
<form action="user.php" method="post">
    <div>
        <label for="user_email">Email</label>
        <input type="email" name="email" id="user_email"/>
    </div>
    <div>
        <label for="user_password">Password</label>
        <input type="password" name="password" id="user_password"/>
    </div>
    <input type="submit" value="Ajouter">
</form>

<?php
if (!empty($_POST['email']) && !empty($_POST['password'])) {

    $servername = "docker-lamp-mysql";
    $username = "root";
    $password = "p@ssw0rd";
    $dbname = 'users_management';
    $conn = null;

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
    $hash = password_hash($userPassword, PASSWORD_BCRYPT);

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $stt = $conn->prepare("INSERT INTO `users` (`login`,password_hash) VALUES (?,?)");
        $stt->bindParam(1, $email);
        $stt->bindParam(2, $hash);

        $stt->execute();

        echo "Utilisateur bien enregistré";
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>
</body>
</html>