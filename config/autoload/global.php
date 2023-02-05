<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    'db' => [
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=pai;host=localhost', // xampp
        //'dsn' => 'mysql:dbname=pai;host=db', // docker
        'driver_options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
        ],
        'username' => 'root',
        'password' => '', // xampp
        //'password' => 'admin', // docker
    ],
    'mail' => [
        'name' => 'imap.wit.edu.pl',
        'host' => 'imap.wit.edu.pl',
        'port' => 465,
        'connection_class' => 'login',
        'connection_config' => [
            'username' => 'wprowadz_login',
            'password' => 'wprowadz_haslo',
            'ssl' => 'ssl',
        ],
        'from' => [
            'email' => 'konto@wit.edu.pl',
            'name' => 'Administrator strony',
        ],
        'to' => 'konto@wit.edu.pl',
    ],
];
