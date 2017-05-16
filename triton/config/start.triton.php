<?php
require_once  __DIR__ . '/config.triton.php';

global $configs, $databases;

if(file_exists(__DIR__ . '/../../' . $configs['databases']))
{
    require_once __DIR__ . '/../../' . $configs['databases'];
}
else
{
    require_once __DIR__ . '/../../' . $configs['databases'];

}
foreach ($databases as $dbname => $database)
{
    if($database['driver'] == 'mysql' || $database['driver'] == 'mariadb')
    {
        $GLOBALS['_neptune']['databases'][$dbname] = new \PDO('mysql:host=' . $database['host'] . ';dbname=' . $dbname . ';charset=' . $database['charset'], $database['user'], $database['pass']);
    }
}
