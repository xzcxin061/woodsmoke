<?php
namespace app\controller;
use think\facade\Request;
use think\facade\View;
use \think\facade\Route;
use app\model\User;

class Copy
{
    public function mytest()
    {
        $file = fopen ("test.txt", "w");
        fwrite ($file, var_export (['1'=>1], true));
        fclose ($file);
        echo "门面/依赖注入测试";
    }

    /**
     * 请求变量
     * 没有必要在控制器中判断请求类型再来执行不同的逻辑，完全可以在路由中进行设置。
     * 我理解上边这句话的意思是相同的路由地址，使用不同的请求类型和不同的路由表达式。当然也可以请求不同的路由地址。
     */
    public function request_param($id)
    {
        echo <<<'EOF'
        打印：var_dump(Request::has('request_test', 'post')); <br/>
        EOF;
        echo "路径：【app\controller\Copy=>request_param】<br/>输出：<br/>";
        var_dump(Request::has('request_test', 'post'));
        View::assign('names', "你好，");
        View::assign('aaaaa', "世界！");
        return View::fetch('index/copy');
    }

    /**
     * 请求变量
     * 没有必要在控制器中判断请求类型再来执行不同的逻辑，完全可以在路由中进行设置。
     */
    public function request_test($id)
    {
        echo <<<'EOF'
        打印：var_dump(Request::has('request_test', 'post')); <br/>
        EOF;
        echo "路径：【app\controller\Copy=>request_test<br/>输出：<br/>";
        echo "<br/>";
        var_dump(Request::has('request_test', 'post'));echo "<br/>";
        var_dump(Request::has('aaaaaa', 'post'));echo "<br/>";
        var_dump(Request::has('bbbbbb', 'post'));echo "<br/>";
        // 经测试isAjax有效
        echo "【isAjax:】";var_dump(Request::isAjax());echo "<br/>";echo "<br/>";
        echo "【method:】";var_dump(Request::method());echo "<br/>";echo "<br/>";
        echo "【method->true:】";var_dump(Request::method(true));echo "<br/>";echo "<br/>";
    }

    public function archive($id=0)
    {
        // var_dump(Request::controller());echo "<br/>";
        // $test = random_int(1,10); 
        // $page = Request::param('page'); 
        // $method = Request::param('method'); 
        // $domain = Request::domain();
        // $url = Request::url();
        // $url = $domain.$url;
        // // echo "【url：】";echo $url;echo "<br/>";
        // $curl = curl_init();
        // curl_setopt($curl, CURLOPT_URL, $url);
        // curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        // curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        // curl_setopt($curl, CURLOPT_HEADER, FALSE);
        // curl_setopt($curl, CURLOPT_NOBODY, FALSE);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, FALSE);
        // // curl_setopt($curl, CURLOPT_PROXYUSERPWD, 'root:xzcxin061');
        // curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        // curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        // curl_exec($curl);
        // $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // echo "【httpCode：】";var_dump($httpCode);echo "<br/>";
        // // curl本地请求超时，已找到原因和方案，浪费时间先不搞了。
        // // 数据传输的最大允许时间超时的话，出错提示形如：Operation timed out after 10001 milliseconds with 0 bytes received
        // // 在用户访问一个php页面的时候nginx已将该进程占用，在该进程又发起http请求时，nginx发现这个仅有的进程已被占用而造成阻塞，就这样造成了死锁，一直到超时。
        // echo "【curlError：】";var_dump(curl_error($curl));echo "<br/>";
        // curl_close($curl);
        // echo不被缓存，只有return被缓存
        // echo "【method】：";echo $method;echo "<br/>";
        // echo "【page】：";echo $page;echo "<br/>";
        // echo "【test】：";
        // echo "\n";
        // echo response()->getCode();echo "\n"; // 这个只能获取304和200，手动设置状态码除外
        // return json(['aaa'=>123, 'bbb'=>456]);
        // return response("response返回html");
        // return view('index/copy', ['names'=>11111, 'aaaaa'=>22222]);
        // return jsonp(['aaa'=>1234, 'bbb'=>5678]);

        /* 注意：php写入xml时，不允许在header()函数前有任何输出。如果有，
        报错信息：This page contains the following errors:
                    error on line 1 at column 1: Document is empty
                Below is a rendering of the page up to the first error.
        */

        // return xml(['aaa'=>777, 'bbb'=>888], 201, ['Content-Type: text/xml'], ['root']);
        // return redirect('/req/456789.html', 302);

        /* 报Call to undefined function think\finfo_open() 错误,
            查看命令：php -i|grep fileinfo，可以查看fileinfo配置真实路径，安装扩展 即可（oneinstack官方教程）。
            因为download()只下载文件，不会对html页面做任何刷新或者操作，
            所以停留在报错页面是正常的(可能被误导)，不喜欢看报错重新打开页面就好了。
        */
        return download('public/static/style.css');
        // return download('CentOS8.session.sql');
        // 以上响应输出测试完毕

    }

    public function resparam()
    {
        $data = [
            'aaa' => 1,
            'bbb' => 2,
            'ccc' => 3,
            'ddd' => 4,
            'eee' => 5
        ];
        // var_dump('&lt;aaaaa&gt;');
        // return response()->data(json_encode($data));
        return json()->data($data);
        return response(json_encode($data))->getContent();
        // return response()->data(json_encode($data))->getContent();
    }
/**
 * 避坑指南：findOrEmpty，findOrFail只支持查询单个数据，不支持查询数据集。
 * 所以不能用于分页查询。
 * 避坑指南：判断空模型，用isEmpty()
 * 判断空数组或null,用empty()
 */
    public function getUser()
    {
        // $param = Request::param();
        // $id = $param['id']?:'';
        // $user = User::where('id', $id)->findOrEmpty();
        // $user = User::where('id', $id)->findOrFail();
        // var_dump($user);

        // 查询分页
        $num = 2;
        $where = array('status'=> 1);
        $list = User::where($where)->paginate($num, false);
        $page = $list->render();
        if($list-> isEmpty())
        {
            echo "空模型！";
            // return false;
        }


        // 模型查询：查询单个数据不存在返回空模型，返回模型和返回数组是模型查询跟数据库查询的区别
        $test = User::findOrEmpty();
        echo <<<'EOF'
        打印来自模型外部【var_dump($test->isEmpty()); 】：<br/>
        EOF;
        var_dump($test->isEmpty());echo "<br/>";
        $test = User::find(); // 空数据返回null
        echo <<<'EOF'
        打印来自模型外部【var_dump(Empty($test)); 】：<br/>
        EOF;
        var_dump(Empty($test));echo "<br/>";
        // 模型内部数据库查询
        $test = User::getUsers(1);

        return View('getuser', ['list'=>$list, 'page' => $page]);
    }
}