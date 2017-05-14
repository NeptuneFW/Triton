<?php

foreach (glob('./*') as  $value)
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