<?php
declare (strict_types = 1);

namespace app\middleware;

class Auth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next, ...$extra_params)
    {
        // 测试多个中间件
        var_dump($extra_params);echo "<br/>";
        // 打印为NULL，是因为还没有发起Http请求。
        echo "【app\middleware\Auth\handle->request->name1】";var_dump($request->name);echo "<br/>";
        // 重定义不改变request->param('name')获取
        $request->name = 333;
        echo "【app\middleware\Auth\handle->request->name2】";var_dump($request->name);echo "<br/>";
        echo "【app\middleware\Auth\handle】";echo "这是中间件Auth！！";echo "<br/>";
        $request -> auth_pagram = "这是auth中间件传参！";
        return $next($request);
    }
}
