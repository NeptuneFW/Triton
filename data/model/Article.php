<?php
/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 24.05.2017
 * Time: 20:51
 */

namespace Data\Model;

class Article extends \Triton
{
    protected static
        $db = 'ntblog',
        $table = 'articles';

    public function user()
    {
        return $this->belongsTo('Data\Model\User');
    }

    public function likes()
    {
        return $this->belongsToMany('Data\Model\Like');
    }

    public function comments()
    {
        return $this->belongsToMany('Data\Model\Comment');
    }
}