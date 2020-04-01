<?php

use think\migration\Seeder;

class User extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run ()
    {
        $this->table(implode('_', array_filter([config('admin.prefix'), config('admin.table')])))
            ->insert([
                ['name' => '超级管理员', 'account' => 'User', 'password' => password_hash('111111', PASSWORD_DEFAULT), 'comment' => '超级管理员'],
            ])->save();
    }
}