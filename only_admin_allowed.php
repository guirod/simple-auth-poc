<?php
include_once "autoload.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
if (isset($_SESSION['user'])) {
    $user = unserialize(base64_decode($_SESSION['user']));
    
    if ($user->isAdmin()) {
?>
<h2>Bonjour <?= htmlspecialchars($user->getLogin())?></h2>
<?php
    } else {
        echo "Au revoir";
    }
    if(isset($_GET['search'])) {
        echo "rechercher : " . $_GET['search'];

    }
}
?>
<a href="http://127.0.0.1/github_pocs/simple-auth-poc/only_admin_allowed.php?search=%3Cscript%3Ealert(document.cookie);%3C/script%3E">click</a>
</body>
</html>
