<?php
include_once "autoload.php";

use Model\Group;

session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un groupe</title>
</head>

<body>
    <form action="add_group.php" method="post">
        <div>
            <label for="group_name">Nom du groupe</label>
            <input type="text" name="name" id="group_name">
        </div>
        <input type="submit" value="Ajouter">
    </form>

    <?php
    if (!empty($_POST['name'])) {
        $group = new Group();
        $group->setName($_POST['name']);
        $group->save();
    }
    ?>
</body>

</html>