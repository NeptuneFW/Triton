<?php

namespace Data\Model;

class User extends \Triton
{
    protected static
        $db = 'ntuser',
        $table = 'users';

    public function articles()
    {
        return $this->hasMany('Data\Model\Article');
    }

    public function likes()
    {
        return $this->hasMany('Data\Model\Article');
    }

}