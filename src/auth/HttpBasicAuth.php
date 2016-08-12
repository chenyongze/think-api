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
        'realm'    => 'api',
        'password' => null
    ];

    public function authenticate()
    {
        $username = $this->getAuthUser();
        $password = $this->getAuthPassword();
        $provider = $this->provider;

        $identity = $provider::loginByAccessToken($username);

        if ($identity === null) {
            $this->handleFailure();
        }

        if ($this->config['password'] !== null && $identity[$this->config['password']] != $password) {
            $this->handleFailure();
        }

        return $identity;
    }

    protected function getAuthUser()
    {
        return $this->request->server('PHP_AUTH_USER');
    }

    protected function getAuthPassword()
    {
        return $this->request->server('PHP_AUTH_PW');
    }

    public function challenge(Response $response)
    {
        $response->header('WWW-Authenticate', "Basic realm=\"{$this->config['realm']}\"");
    }

}