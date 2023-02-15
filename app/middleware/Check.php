<?php
declare (strict_types = 1);

namespace app\middleware;

class Check
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        // 前置行为中间件写法
        // echo "【app\middleware\handle\Before】";echo "这是一个前置行为中间件！！！！在其他行为之后（比如：服务），在http请求之前拦截http";echo "<br/>";
        // return $next($request);
        // 后置行为中间件写法
        $response = $next($request);
        echo "【app\middleware\handle\After】";echo "这是一个后置行为中间件！！！！在其他行为之前(比如服务)，在http请求之前拦截http";echo "<br/>";
        return $response;
    }

    /**
     * 在end方法里面不能有任何的响应输出。因为回调触发的时候请求响应输出已经完成了。
     */
    public function end(\think\Response $response)
    {
        // 回调行为
        echo "【app\middleware\Check\\end】";echo "这是一个中间件请求结束前的回调机制，在http请求之后回调。";
    }
}
