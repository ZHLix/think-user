<?php


namespace zhlix\user;

use Firebase\JWT\JWT;
use think\Exception;
use zhlix\helper\facade\Rsa;
use zhlix\helper\facade\Http;
use zhlix\user\model\User as MUser;

class User
{
    /**
     * 用户信息
     *
     * @var array
     */
    protected $user = [];

    /**
     * 是否登录
     *
     * @var bool
     */
    protected $isLogin = false;

    /**
     * 错误信息
     *
     * @var string
     */
    protected $errorMessage = '';

    /**
     * 初始化
     * User constructor.
     */
    public function __construct ()
    {
        if (request()->header('jwt-token')) {
            try {
                $this->user    = JWT::decode(request()->header('jwt-token'), Rsa::getPublicKey(), ['RS256'])->data;
                $this->isLogin = true;
            } catch (\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
                $this->errorMessage = '签名不正确';
            } catch (\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
                $this->errorMessage = '签名在某个时间点之后才能用';
            } catch (\Firebase\JWT\ExpiredException $e) {  // token过期
                $this->errorMessage = 'token过期';
            } catch (Exception $e) {  //其他错误
                $this->errorMessage = '其他错误';
            }
        }
    }

    /**
     * 登录
     *
     * @param array $data
     *
     * @return string
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login ($data = [])
    {
        if (empty($data)) $data = encrypted_data();

        // 验证字段
        validate(\zhlix\user\validate\User::class)->scene('login')->check($data);

        $user = MUser::where(['account' => $data['account']])->find();

        // 验证用户
        if (is_null($user)) {
            throw new Exception('账号或密码错误');
        }
        // 验证密码
        if (!password_verify($data['password'], $user['password'])) {
            throw new Exception('账号或密码错误');
        }

        $nowtime = time();
        $token   = [
            'iss'  => Http::urlHeader(), //签发者
            'aud'  => Http::urlHeader(), //jwt所面向的用户
            'iat'  => $nowtime, //签发时间
            'nbf'  => $nowtime, //在什么时间之后该jwt才可用
            'exp'  => $nowtime + 60,// 14400, //过期时间-4hour
            'data' => [
                'id'      => $user['id'],
                'name'    => $user['name'],
                'account' => $user['account'],
                'comment' => $user['comment'],
            ],
        ];

        $this->user = $token['data'];

        return JWT::encode($token, Rsa::getPrivateKey(), 'RS256');
    }

    /**
     * @return bool
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit ()
    {
        if (empty($data)) $data = encrypted_data();
        // 验证字段
        validate(\zhlix\user\validate\User::class)->scene('edit')->check($data);

        $user = MUser::find($this->user->id);

        if (!password_verify($data['re_password'], $user['password'])) {
            throw new Exception('原密码输入错误');
        }

        $res = $user->save(['password' => password_hash($data['password'], PASSWORD_DEFAULT)]);

        if (!$res) throw new Exception('未知错误');

        return true;
    }

    /**
     * 判断是否登录
     *
     * @return bool
     */
    public function is_login (): bool
    {
        return $this->isLogin;
    }

    /**
     * 获取用户信息
     *
     * @return array
     */
    public function getUser (): array
    {
        return $this->user;
    }

    /**
     * 获取错误信息
     *
     * @return string
     */
    public function error_message ()
    {
        return $this->errorMessage;
    }
}