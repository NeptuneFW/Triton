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
foreach ($articles as $article) {
    $user = $article->user[0];
    echo '<h1>' . $article->title . '</h1> <br/> <span> Yazar : ' . $user->name . ' ' . $user->surname . ' <time> ' . $article->created . ' </time></span> <br/> ' . html_entity_decode($article->content) . ' <br/>';
    echo '<br/>Beğenenler : ';
    foreach ($article->likes as $like) {
        echo '<br/>' . $like->user[0]->name . ' ' . $like->user[0]->surname;
    }
    echo '<br/><br/> Yorumlar';
    foreach ($article->comments as $comment) {
        echo '<br/>' . $comment->user[0]->name . ' ' . $comment->user[0]->surname . ' <small><time>' . $comment->created . '</time></small>';
        echo '<br/>' . $comment->text . '<br/>';
    }
    echo '<hr/>';
}


?>
<style>
    /*! Typebase.less v0.1.0 | MIT License */
    /* Setup */
    html {
        /* Change default typefaces here */
        font-family: serif;
        font-size: 137.5%;
        -webkit-font-smoothing: antialiased;
    }
    /* Copy & Lists */
    p {
        line-height: 1.5rem;
        margin-top: 1.5rem;
        margin-bottom: 0;
    }
    ul,
    ol {
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
    }
    ul li,
    ol li {
        line-height: 1.5rem;
    }
    ul ul,
    ol ul,
    ul ol,
    ol ol {
        margin-top: 0;
        margin-bottom: 0;
    }
    blockquote {
        line-height: 1.5rem;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
    }
    /* Headings */
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        /* Change heading typefaces here */
        font-family: 'BeaufortforLOL-Regular';
        margin-top: 1.5rem;
        margin-bottom: 0;
        line-height: 1.5rem;
    }
    h1 {
        font-size: 1.942rem;
        line-height: 1.9rem;
        margin-top: 1rem;
    }
    h2 {
        font-size: 2.828rem;
        line-height: 3rem;
        margin-top: 3rem;
    }
    h3 {
        font-size: 1.414rem;
    }
    h4 {
        font-size: 0.707rem;
    }
    h5 {
        font-size: 0.8713333333333333rem;
    }
    h6 {
        font-size: 0.3535rem;
    }
    /* Tables */
    table {
        margin-top: 1.5rem;
        border-spacing: 0px;
        border-collapse: collapse;
    }
    table td,
    table th {
        padding: 0;
        line-height: 33px;
    }
    /* Code blocks */
    code {
        vertical-align: bottom;
    }
    /* Leading paragraph text */
    .lead {
        font-size: 1.414rem;
    }
    /* Hug the block above you */
    .hug {
        margin-top: 0;
    }
</style>
