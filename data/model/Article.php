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
        $table = 'article';

    public function user()
    {
        return $this->belongsTo('Data\Model\User');
    }
}