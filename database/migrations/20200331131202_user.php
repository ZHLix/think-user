<?php

use think\migration\Migrator;
use think\migration\db\Column;

class User extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change ()
    {
        $this->table(implode('_', array_filter([config('admin.prefix'), config('admin.table')])))
            ->addColumn('name', 'string', ['limit' => 32, 'comment' => '姓名'])
            ->addColumn('account', 'string', ['limit' => 32, 'comment' => '账号'])
            ->addColumn('password', 'string', ['comment' => '密码'])
            ->addColumn('comment', 'string', ['comment' => '备注'])
            ->addColumn('status', 'boolean', ['default' => 1, 'comment' => '启用状态'])
            ->addColumn('sort', 'integer', ['default' => 0, 'comment' => '排序'])
            ->addTimestamps()
            ->addSoftDelete()
            ->addIndex(['name', 'account'], ['unique' => true])
            ->create();
    }
}
