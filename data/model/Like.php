<?php
/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 26.05.2017
 * Time: 20:10
 */

namespace Data\Model;


class Like extends \Triton
{
    protected static
        $db = 'ntblog',
        $table = 'likes';

    public function user()
    {
        return $this->belongsTo('Data\Model\User');
    }

    public function article()
    {
        return $this->belongsToMany('Data\Model\Article');
    }
}