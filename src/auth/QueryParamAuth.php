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

class QueryParamAuth extends Auth
{

    protected $config = [
        'token_name' => 'access-token'
    ];

    public function authenticate()
    {
        $accessToken = $this->request->param($this->config['token_name']);
        $provider    = $this->provider;

        $identity = $provider::loginByAccessToken($accessToken);
        if ($identity === null) {
            $this->handleFailure();
        }

        return $identity;
    }

}