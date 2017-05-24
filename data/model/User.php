<?php

namespace Data\Model;

class User extends \Triton
{
    protected static
        $db = 'naber',
        $table = 'user';
    /*
    public function forBuild()
    {
        $this->name->oninsert = function($string) { ucwords($string->name); };
        $this->name->onupdate = function($string) { ucwords($string->name); };
        $this->name->ondelete = function () { };
    }
    */
}