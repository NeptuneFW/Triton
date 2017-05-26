<?php
/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 26.05.2017
 * Time: 20:10
 */

namespace Data\Model;


class Likes
{
    protected static
        $db = 'ntblog',
        $table = 'likes';

    public function user()
    {
        return $this->hasMany('Data\Model\User');
    }
}