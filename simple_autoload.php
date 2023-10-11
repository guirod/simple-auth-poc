<?php

spl_autoload_register(function () {
    // base directory for the namespace prefix
    $base_dir = __DIR__ . "/";

    // renseigner tous les fichiers qui doivent être loadés
    $files = [
        $base_dir . 'Model/Connexion.php',
        $base_dir . 'Model/User.php',
        $base_dir . 'Model/Group.php',
        $base_dir . 'Model/UserGroup.php',
        $base_dir . 'Model/iCrud.php',
    ];

    foreach($files as $file) {
        // if the file exists, require it
        if (file_exists($file)) {
            require_once $file;
        }
    }
});