<?php
namespace app\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Config;
use think\Request;
// use think\facade\Request;
use app\controller\Copy;
use app\extend\Cont;
// use app\service\MyService;
use app\controller\TrendLine;
use think\facade\Route;
use app\model\User;
use app\model\UserProfile;
use think\Container;
use think\db\Query;
use think\facade\Event;

class Index extends BaseController
{
    protected $trend;

    // 修改代码，刷新页面延时生效的问题主要是php7.x引起的，网上说的在php.ini里修改opcache配置是不对的。
    // 正确方法是phpinfo()打印找到opcache.ini的真实路径，然后修改两处配置并重启apache/nginx服务（systemctl命令,不会就重启系统）：
    // opcache.revalidate_freq=0和opcache.enable = 0。
    public function index()
    {
        // 测试 依赖注入
        // var_dump($this->request);
        // 测试 中间件
        // return redirect('hello/1');
        // 测试模板渲染 -
        // View::assign('name','测试');
        // echo DIRECTORY_SEPARATOR;
        // return View::fetch("index/index");
        // dump(app('myserv')->mytest());
        
        // 计算夹角
        // $arr = [28.6,19.3,40.5,32.6,48.9,90,29.2,39.85,46.7,37.4];
        // $test = new TrendLine($arr);
        // var_dump($test->getKB());echo "<br/>";
        // $zy = 1.81;
        // $zx = 1.00;
        // $zz = sqrt(pow($zy, 4) + pow($zx, 4));
        // echo "asin计算：".round(asin(1.81/$zz)/pi()*180, 2);
        // echo "<br/>";
        // echo "sin函数：".sin(90*pi()/180);echo "<br/>";
        // echo "asin函数：".round(asin(0.7079)*180/pi(), 2);
        return View::fetch('index', ['name'=>'异常测试情况']);
    }

    public function hello(User $user)
    {
        Container::getInstance()->resolving(\think\Cache::class,function($instance,$container) {
            // ...
            // var_dump($instance);echo "<br/>";echo "<br/>";echo "<br/>";
            // var_dump($container);echo "<br/>";echo "<br/>";echo "<br/>";
        });
        // var_dump($user);
    }

    public function copy(Copy $copy)
    {
        // echo $this->request->action();
        View::assign('name','门面测试');
        // echo \app\facade\Copy::mytest();
        // 全局变量
        // View::assign('namesss','自定义依赖注入测试');
        // echo $copy->mytest();
        // return View::fetch('copy', ['namesss'=>'模板变量输出测试！！']);
        return View::fetch();
    }

    /**
     * 依赖注入
     * invoke助手函数
     * 支持给构造器传参(对应目标类构造方法参数，PHP)
     * 去掉目标类构造方法的注释
     */
    public function aaa()
    {
        // $b = invoke('app\controller\Mydoc');
        // echo $b->firstapp();
        // --------------开始--------------------------
        // 第1种：不传参，目标类->方法
        // $s = invoke(['app\extend\Cont', 'bbbb']);echo $s;
        // 第2种：不传参，目标类
        // $s = invoke('app\extend\Cont');
        // echo $s->bbbb();
        // 第3种：当目标类不含构造方法，给目标类中的其他方法传参
        // $a = [1,2,3,4];
        // $s = invoke(['app\extend\Cont', 'bbbb'], [$a]);var_dump($s);
        // 第4种：当目标类包含构造方法时，构造方法会初始化执行：PHP。invoke第2个（数组）参数默认传给构造方法对应的参数。
        // 如果invoke第1个参数包含目标类中的其他方法，传参会报错。
        // $arr = [28.6,19.3,40.5,32.6,48.9,90,29.2,39.85,46.7,37.4];
        // $trend = invoke('app\controller\TrendLine', [$arr]);
        // var_dump($trend->getKB());echo "<br/>";
        // ---------------结束--------------------------
        // 匿名函数测试：斐波那契数列...
        // $fib(5)
        // =$fib(4)+$fib(3)
        // =($fib(3)+$fib(2))+($fib(2)+$fib(1))
        // =(($fib(2)+$fib(1))+($fib(1)+$fib(0)))+(($fib(1)+$fib(0))+$fib(1))
        // =(($fib(1)+$fib(0))+$fib(1))+($fib(1)+$fib(0)))+(($fib(1)+$fib(0))+$fib(1))
        // =(((1+0)+1)+(1+0))+((1+0)+1)
        // =5
        $fib =function($n)use(&$fib) {
            if($n == 0) return 0;
            if($n == 1) return 1;
            return $fib($n - 1) + $fib($n - 2);  
        };  
        echo $fib(5) . "\n";// 5  
        $lie =$fib;
        $fib =function(){die('error');};//rewrite $fib variable   
        echo $lie(5);// error   because $fib is referenced by closure
    }

    /**
     * 依赖注入
     * 对参数进行对象类型约束
     * 不支持给构造器传参(要在目标类中额外写一个方法获取参数)
     * 注释掉目标类的构造方法，使用自定义的setParam方法
     */
    public function bbb(TrendLine $trendline)
    {
        $arr = [28.6,19.3,40.5,32.6,48.9,90,29.2,39.85,46.7,37.4];
        $this->trend = $trendline;
        // 初始化目标类变量
        $this->trend->setParam($arr);
        // var_dump($this->trend->getKB());
    }

    /**
     * 对某个函数或者闭包使用依赖注入
     */
    public function ccc()
    {
        // $ccc = 3;
        // $result = invoke(function(Cont $cont) use($ccc) 
        // {
        //     echo $ccc;echo "<br/>";
        //     var_dump($cont->bbbb($ccc));
        // });
        // 将闭包绑定到容器
        bind('sayHello', function ($name) {
            return 'hello,' . $name;
        });
        // var_dump(app('sayHello',['Say！']));
    }

    /**
     * 服务测试
     */
    public function ddd()
    {
        // var_dump(app('myserv'));echo 2;echo "<br/>";
        // var_dump(app('myserv1'));echo 3;echo "<br/>";
        // var_dump(app()->getService('myserv'));echo 4;echo "<br/>";
        // var_dump(app()->getService('myserv'));echo 5;echo "<br/>";
    }

    /**
     * @description: 中间件测试
     * @param {Request} $request
     * @return {*}
     */
    public function eee(Request $request)
    {
        echo "【app\Controller\Index\\eee】";echo $request->auth_pagram;echo "<br/>";
        echo "【app\Controller\Index\\eee->name】";echo $request->param('name');echo "<br/>";
        echo "【app\Controller\Index\\eee】";echo "在这里测试路由中间件。";echo "<br/>";
    }

    /**
     * 事件测试
     */
    public function fff(User $user)
    {
        // 绑定事件表示，也可以在应用的event.php设置
        // Event::bind(['UserLogin' => 'app\event\UserLogin']);
        // 使用助手函数调用
        // event('UserLogin', $user);
        // 使用Event方法调用
        // 测试事件监听
        // Event::trigger('UserLogin1');
        Event::trigger(app()->make(\app\event\UserLogin::class));
        // Event::trigger(app()->make('UserLogin1'));
        // 测试事件订阅
        Event::trigger('Play');
        // 测试事件类监听
        Event::trigger($user);
        // 未绑定事件标识的调用方法
        // event('app\event\UserLogin');
        // Event::trigger('app\event\UserLogin');
        
    }

    /**
     * 后续将择机测试观察者模式、发布订阅模式
     */
    public function ggg()
    {
        
    }


    /**
     * @Author: WoodSmoke
     * @description: 测试路由
     * @return {*}
     */
    public function aaaaa(Request $request)
    {
        // 使用app()助手函数
        $id1 = app()->request->param('id');
        // 使用request()助手函数
        $id2 = request()->param('id');
        // 仅用于think\facade\Request，不能和think\Request一起引入。名称冲突。
        // $id3 = $request::param('id');
        echo "【app\contoller\index\aaaaa】";echo "id1:".$id1;echo "<br/>";
        echo "【app\contoller\index\aaaaa】";echo "id2:".$id2;echo "<br/>";
        // echo "【app\contoller\index\aaaaa】";echo "id3:".$id3;echo "<br/>";
        echo "【app\contoller\index\aaaaa】";echo "nm:";echo request()->param('nm');echo "<br/>";
        echo "【app\contoller\index\aaaaa】";echo "abc:";echo request()->param('abc');echo "<br/>";
        echo "【app\contoller\index\aaaaa】";echo "edf:";echo request()->param('edf');echo "<br/>";
        echo "【app\contoller\index\aaaaa】";echo "url:";echo url('Index/aaaaa', ['id'=>7555, 'nm'=>3613], true, true);echo "<br/>";
        echo "【app\contoller\index\aaaaa】";echo "url:";echo url('idrm1', ['id'=>55, 'nm'=>33], true, true);echo "<br/>";
    }

    /**
     * 随便测试一下调用百度地图
     */
    public function maps()
    {
        // 这是浏览器端APK，服务端APK不支持浏览器端使用
        $Ak = "4Ges25jYdvDPrdGgbIedivYlsfvfCyk8";
        $baseUrl = "https://api.map.baidu.com/direction/v2/driving?";
        $origin = "";
        $destination = "";
        $request = "Get";
        $getRegion = "https://api.map.baidu.com/place/v2/search?query=ATM机&tag=银行&region=北京&output=json&ak=qjZIqKemNWV2jL1Uqu5W2oOSmKjLzhMs"; //GET请求

        // http://lbsyun.baidu.com/apiconsole/qjZIqKemNWV2jL1Uqu5W2oOSmKjLzhMs
    }

    /**
     * 测试模型关联:一对一关联
     */
    public function get_profile()
    {
        // 注意对象不能为空，应该做判断
        $user = User::find(2);
        // 注意：User和userProfile的属性调用方式有点区别
        $a = $user->user_profile->realname; // userProfile的
        // 使用下面的语句也一样，区别就是user_profile和userProfile，关联属性系统会自动转换（驼峰转下划线）
        // $a = $user->userProfile->realname;
        print_r($a);echo "<br/>";
        $h = $user->username; // User的
        print_r($h);echo "<br/>";
        // hasWhere查询，存在N+1问题
        $b = User::hasWhere('userProfile', ['createtime' => '1355388797'])->select();
        foreach($b as $v){
            echo "hasWhere:";echo $v->user_profile->realname;echo "<br/>";
        }
        // with使用的是in查询，避免N+1问题
        $c = User::with('userProfile')->select();
        foreach($c as $v){
            echo "with:";echo $v->user_profile->realname;echo "<br/>";
        }
        // hasWhere是关联查询，但where是普通条件查询可以和with配合使用
        $d = User::with(['userProfile' => function(Query $query){
            // 有的讨论说使用where要加上getQuery方法，待验证，https://www.cnblogs.com/dengxiaobo/p/16173313.html
            $query->field('user_id, realname')->where('sex', '=', 1);
        }])->where('status', '>', 1)->select();
        echo User::getLastSql();echo "<br/>";
        
        foreach($d as $v){
            if($v->user_profile){
                echo "匿名函数：";echo $v->user_profile->realname;echo "<br/>";
            }else{
                echo "匿名函数：";var_dump($v->user_profile);echo "<br/>";
            }
            
        }
    }
    /**
     * withJoin测试
     */
    public function get_article()
    {
        // 默认使用INNER JOIN连接
        $a_test = User::withJoin('article')->limit(5)->select();
        // 手动指定使用left join连接
        $b_test = User::withJoin('article', 'left')->limit(5)->select();
        // 使用多个join
        $c_test = User::withJoin(['article', 'userProfile'], 'left')->limit(5)->select();
        
        // 关联保存，使用了mysql的replace into tbl_name set col_name=value, ...语句
        /**
         * 1.根据主键或唯一索引判断记录是否已存在，所以插入数据的表必须要有主键或者唯一索引(备注：索引可以是多个字段同时创建的)！否则的话，REPLACE INTO 会直接插入数据（相当于INSERT），会导致表中出现重复数据。
         * 2.如果不写某个字段的值则会使用默认值，如果该字段没有定义默认值则报错。
         * 3.要使用REPLACE INTO，必须同时拥有表的INSERT和 DELETE权限。
         */
        // 关联保存新增数据的方法，实现原理mysql的REPLACE INTO
        $a_save = User::find(3);
        $a_save->userProfile()->save(['realname' => '李四', 'sex'=>2, 'email'=>'lisi2022@hotmail.com', 'img'=>'https://img.100run.com/201212/50ca9afd94e6c.jpg', 'login_count'=>'1215', 'address' => '天津市河北区']);
        echo User::getLastSql();echo "<br/>";

        // 关联保存更新数据的方法
        $b_save = User::find(3);
        $b_save->user_profile->save(['address' => '天津市和平区']);
        // 或者
        // $b_save->userProfile->address = "天津市和平区";
        // $b_save->userProfile->save();
        echo User::getLastSql();echo "<br/>";
        // var_dump($a_save->userProfile()); // 返回hasOne对象
        // echo "<br/>";echo "<br/>";echo "<br/>";echo "<br/>";echo "<br/>";
        // var_dump($a_save->userProfile); // 返回userProfile对象
    }

    /**
     * 相对关联
     */
    public function get_user()
    {
        $user_profile = UserProfile::find(3);
        echo $user_profile->user->id;
    }

    /**
     * 绑定属性到父模型
     */
    public function get_bind_profit()
    {
        // 使用预载入查询的情况
        $user = User::with('user_profile')->find(4);
        print_r($user->address);echo "<br/>";
        print_r($user->email);echo "<br/>";
        // 不使用预载入查询的情况
        $user = User::find(5);
        $user->appendRelationAttr('userProfile', ['img']);echo "<br/>";
        var_dump($user->img);echo "<br/>";
    }
}