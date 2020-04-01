<?php

namespace zhlix\user\validate;


class User extends \think\Validate
{
    protected $rule = [
        'account|账号'  => 'require',
        'password|密码' => 'require|length:6,18',
        'comment|备注'  => 'require',
        'captcha|验证码' => 'require|captcha',
    ];

    public function sceneLogin ()
    {
        if (env('app_debug')) {
            $this->only(['account', 'password']);
        }
        return $this;
    }

    public function sceneEdit ()
    {
        if (env('app_debug')) {
            $this->only(['account', 'password']);
        }
        $this->only(['password'])
            ->append('password', 'confirm:password_verify')
            ->append('re_password', 'require|different:password')
            ->append('password_verify', 'require');
        return $this;
    }
}