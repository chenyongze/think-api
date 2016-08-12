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

class HttpBearerAuth extends Auth
{

    public function authenticate()
    {
        $authHeader = $this->request->header('Authorization');

        $provider = $this->provider;
        if ($authHeader !== null && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            $identity = $provider::loginByAccessToken($matches[1]);

            if ($identity === null) {
                $this->handleFailure();
            }
            return $identity;
        }
    }

}