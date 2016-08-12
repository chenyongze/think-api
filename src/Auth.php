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
namespace think\api;

use think\api\exception\UnauthorizedHttpException;
use think\Request;
use think\Response;

abstract class Auth
{
    /** @var  Authenticatable */
    protected $provider;

    /** @var Request */
    protected $request;

    protected $config = [];

    public function __construct(Request $request, $provider, array $config = [])
    {
        $this->provider = $provider;
        $this->request  = $request;
        $this->config   = array_merge($this->config, $config);
    }

    abstract public function authenticate();

    public function handleFailure()
    {
        throw new UnauthorizedHttpException('You are requesting with an invalid credential.');
    }

    public function challenge(Response $response)
    {

    }

    public function check()
    {
        try {
            $identity = $this->authenticate();
        } catch (UnauthorizedHttpException $e) {
            if ($this->isOptional()) {
                return true;
            }
            throw $e;
        }
        if ($identity !== null || $this->isOptional()) {
            Request::hook('auth', function () use ($identity) {
                return $identity;
            });
            return true;
        } else {
            $this->handleFailure();
            return false;
        }
    }

    protected function isOptional()
    {
        return false;
    }
}