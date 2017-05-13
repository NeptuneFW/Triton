<?php
/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 14.05.2017
 * Time: 01:10
 */

foreach (glob('./*') as  $value)
{
    if($value !== 'index.php')
    {
        require_once $value;
    }
}

$user = new UserModel();
$user->name = "Emirhan";
$user->surname = "Engin";
echo $user->name;
echo $user->surname;