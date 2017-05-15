<?php

use Data\Model\User;

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

$user = new User();
$user->name = "Emirhan";
$user->surname = " Engin";
echo $user;