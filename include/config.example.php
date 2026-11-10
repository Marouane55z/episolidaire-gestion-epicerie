<?php
/**
 * Exemple de configuration de la base de données.
 *
 * Facultatif : sans include/config.php, l'application utilise les valeurs
 * ci-dessous, qui correspondent à une installation locale XAMPP / WAMP / MAMP.
 * Pour les modifier, copier ce fichier en include/config.php (ignoré par Git).
 * Les variables d'environnement EPI_DB_* restent prioritaires.
 */
return [
    'hote' => 'localhost',
    'port' => '3306',
    'base' => 'getcet',
    'utilisateur' => 'root',
    'motDePasse' => '',
];
