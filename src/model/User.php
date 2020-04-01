<?php


namespace zhlix\user\model;


use think\Model;
use think\model\concern\SoftDelete;

class User extends Model
{
    protected $table = '';

    public function __construct (array $data = [])
    {
        $this->table = implode('_', array_filter([config('admin.prefix'), config('admin.table')]));
        parent::__construct($data);
    }

    use SoftDelete;

    protected $autoWriteTimestamp = 'datetime';

    protected $hidden = ['update_time', 'delete_time'];
}