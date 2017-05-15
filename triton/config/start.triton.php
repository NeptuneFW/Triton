<?php

require_once  'config.triton.php';
require_once __DIR__ . '/../../' . $configs['databases'];

foreach ($databases as $dbname => $database)
{
    if($database['driver'] == 'mysql' || $database['driver'] == 'mariadb')
    {
        $GLOBALS['databases'][$dbname] = new \PDO('mysql:host' . $database['host'] . ';dbname=' . $dbname . ';charset=' . $database['charset'], $database['user'], $database['pass']);
    }
}

var_dump($GLOBALS['databases']);