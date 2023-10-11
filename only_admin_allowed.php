<?php
include_once "simple_autoload.php";
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
<h2>Bonjour <?= $user->getLogin()?></h2>
<?php
    } else {
        echo "Au revoir";
    }
}
?>
</body>
</html>
