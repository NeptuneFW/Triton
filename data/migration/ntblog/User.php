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
        $table->timestamp('created_time');
        $table->int('rank');
        $table->int('deleted');
        return $table;
    }

    public function up()
    {
        $user = new UserModel();
        $user->add(['name' => 'Mehmet Ali', 'surname' => 'Peker', 'rank' => 1, 'deleted' => 0]);
        $user->add(['name' => 'Emirhan', 'surname' => 'Engin', 'rank' => 1, 'deleted' => 0]);
        $user->save();
    }

}