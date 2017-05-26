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

$articles = \Data\Model\Article::all();
foreach ($articles as $article)
{
    $user = $article->user[0];
    echo '<h1>' . $article->title . '</h1> <br/> <h5> Yazar : ' . $user->name . ' ' . $user->surname . ' <time> ' . $article->created . ' </time></h5> <br/> ' . html_entity_decode($article->content) . ' <br/>';
}
