<?php

namespace Data\Model;

class User extends \Triton
{
    protected static
        $db = 'ntuser',
        $table = 'user';

    public function forBuild()
    {
        $this->name->oninsert = ucwords($string->name);
        $this->name->onupdate = ucwords($string->name);
        $this->name->ondelete = function () { };

    }
}