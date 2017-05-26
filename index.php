<?php

use Data\Model\User;

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

$users = User::all();
foreach ($users as $user)
{
    foreach ($user->articles as $article)
    {
        var_dump($article);
    }
}
