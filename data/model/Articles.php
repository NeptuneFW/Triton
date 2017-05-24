<?php
/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 24.05.2017
 * Time: 20:51
 */

namespace Data\Model;

class Posts extends \Triton
{
    protected static
        $db = 'ntblog',
        $table = 'articles';

    public function users()
    {
        return $this->belongsTo('Data\Model\Users');
    }
}