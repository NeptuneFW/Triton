<?php

use Data\Model\Users;

define('TRITON_ROOT', __DIR__ . '/triton');

require "triton/config/start.triton.php";
foreach (glob('./triton/triton/*') as  $value)
{
    require_once $value;
}

foreach (glob('./data/model/*') as  $value)
{
    if($value !== 'index.php')
    {
        require_once $value;
    }
}

$users = Users::all('*')->getData();

var_dump($users);


foreach ($users as $user)
{
    foreach($user->articles as $article)
    {
        var_dump($article);
    }
}
