<?php

namespace Data\Model;

class Users extends \Triton
{
    protected static
        $db = 'ntuser',
        $table = 'users';

    public function articles()
    {
        return $this->hasMany('Data\Model\Articles');
    }
}