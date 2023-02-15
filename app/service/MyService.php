<?php
declare (strict_types = 1);

namespace app\service;
use app\extend\Cont;

class MyService extends \think\Service
{
    // 简单绑定到容器
    public $bind = [
        'myserv'    =>    Cont::class,
    ];
    /**
     * 注册服务
     *
     * @return mixed
     */
    public function register()
    {
    	// 将Cont服务绑定到容器
        $this->app->bind('myserv1', Cont::class);
    }

    /**
     * 执行服务
     *
     * @return mixed
     */
    public function boot()
    {
        // 测试启动顺序
        // echo "【think\Service\boot】"; 
    }
}
