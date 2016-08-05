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


use think\Request;

class HmacAuth extends Auth
{

    protected $config = [
        'auth'            => null,
        'app_id_name'     => 'app_id',
        'app_secret_name' => 'token',
        'sign_name'       => 'sign'
    ];

    protected function verifySign()
    {
        //TODO
        return true;
    }


    public function authenticate()
    {
        $provider = $this->provider;

        $identity = $provider::loginByAccessToken($this->request->param($this->config['app_id_name']));

        if (!$this->verifySign()) {
            $this->handleFailure();
        }

        return $identity;
    }

}