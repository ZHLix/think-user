<?php

namespace zhlix\user\facade;


class User extends \think\Facade
{
    protected static function getFacadeClass ()
    {
        return 'zhlix\\user\\User';
    }
}