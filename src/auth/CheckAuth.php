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
use think\api\exception\UnauthorizedHttpException;
use think\Config;
use think\exception\HttpResponseException;
use think\Request;
use think\Response;

class CheckAuth
{
    public function run()
    {
        $config   = Config::get('api.auth');
        $provider = $config['provider'];
        unset($config['provider']);
       
        if (!$provider || !is_subclass_of($provider, '\\think\\api\\Authenticatable')) {
            throw new \InvalidArgumentException('the provider must instanceof \think\api\Authenticatable');
        }

        $class = strpos($config['type'], '\\') ? $config['type'] : '\\think\\api\\auth\\' . ucwords($config['type'] . 'Auth');

        /** @var Auth $auth */
        $auth = new $class(Request::instance(), $provider, $config);
        
        try {
            $auth->check();
        } catch (UnauthorizedHttpException $e) {
            $response = new Response('', 401);
            $auth->challenge($response);
            throw new HttpResponseException($response);
        }
    }
}