<?php

namespace Data\Model;

class Comment extends \Triton
{
    protected static
        $db = 'ntblog',
        $table = 'comments';

    public function article()
    {
        return $this->belongsToMany('Data\Model\Article');
    }

    public function user()
    {
        return $this->belongsTo('Data\Model\User');
    }

}