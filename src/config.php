<?php
$env = parse_ini_file(__DIR__ . '/../.env');
ORM::configure(
    [
        'connection_string' => "mysql:host=" . $env['DB_HOST'] . ";dbname=" . $env['DB'],
        'username' => $env['DB_USER'],
        'password' => $env['DB_PASS']
    ]
);