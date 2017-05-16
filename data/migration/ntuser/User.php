<?php

namespace Data\Migration;
use Data\Model\User as UserModel;

class User extends \TritonMigration
{

    public function table($table)
    {
        $table->increments('id');
        $table->varchar('name');
        $table->varchar('surname');
        $table->varchar('email');
        $table->int('rank');
        $table->int('deleted');
        $table->timestamp('created_at');
        return $table;
    }

    public function up()
    {
        UserModel::add([]);
    }

}