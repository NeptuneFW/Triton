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
        $table->int('age');
        $table->timestamp('created_at');
        return $table;
    }

    public function up()
    {
        UserModel::add([
            'name' => 'Mehmet Ali',
            'surname' => 'Peker',
            'email' => 'maps6134@gmail.com',
            'age' => '12'
        ]);

        UserModel::add([
            'name' => 'Emirhan',
            'surname' => 'Engin',
            'email' => 'whitekod.com2001@gmail.com',
            'age' => '16'
        ]);
    }

}