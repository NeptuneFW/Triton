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
