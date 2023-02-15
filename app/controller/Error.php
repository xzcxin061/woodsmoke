<?php
namespace app\controller;
use think\facade\Env;
use think\facade\Request;
// 空控制器类

class Error{
    public function __call($name, $arguments)
    {
        if(Env::get('app_debug') == true)
        {
            return "哎呀,我们的控制器【".Request::controller()."】找不到了,真是抱歉!";
        } else 
        {
            return "哎呀,我们的页面找不到了,真是抱歉!";
        }
        
    }
}