<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

namespace think\api\auth;

use think\api\Auth;
use think\Response;

class HttpBasicAuth extends Auth
{
    public $auth;

    protected $config = [
        'realm' => 'api'
    ];

    public function authenticate()
    {
        $provider = $this->provider;

        $token = [
            'username' => $this->request->server('PHP_AUTH_USER'),
            'password' => $this->request->server('PHP_AUTH_PW')
        ];

        $identity = $provider::loginByAccessToken($token);

        if ($identity === null) {
            $this->handleFailure();
        }

        return $identity;
    }

    public function challenge(Response $response)
    {
        $response->header('WWW-Authenticate', "Basic realm=\"{$this->config['realm']}\"");
    }

}