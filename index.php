<?php

foreach (glob('./*') as  $value)
{
    if($value !== 'index.php')
    {
        require_once $value;
    }
}

$user = new UserModel();
$user->name = "Emirhan";
$user->surname = " Engin";
echo $user;