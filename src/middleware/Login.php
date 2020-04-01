<?php
declare (strict_types = 1);

namespace zhlix\user\middleware;

use zhlix\user\facade\User;

class Login
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     *
     * @return Response
     */
    public function handle ($request, \Closure $next)
    {
        if (!User::is_login()) {
            return result(null, 400, User::error_message());
        }
        $next($request);
    }
}
