<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use app\Request;
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

// 如果开启了MISS路由(会开启强制路由模式)，首页路由要定义一下
// 如果未开启MISS路由，首页路由不用定义，默认index。
Route::rule('/', 'index');

/**
 * 或者闭包
* MISS路由：Route::miss('public/miss')
*/
Route::miss(function(){
    return '404 Not Found!';
});

// Route::rule('路由表达式', '路由地址', '请求类型');
Route::get('hello/:name', 'Index/hello');
Route::get('user/<id>','Index/hello')->model('\app\model\User');
// Route::get('hellos', 'Index/hello'); 直接访问报错，目标类包含模型注入，必须有主键id
// 测试路由中间件，middleware多个中间件使用数组
Route::rule('middle4', 'Index/eee')
    ->middleware([\app\middleware\Auth::class, \app\middleware\Check::class]);
// 测试预定义别名的路由中间件，middleware多个中间件使用数组
Route::rule('middle3', 'Index/eee')
    ->middleware(['auth', 'check']);
// middleware不使用数组，从check开始的参数会变成额外参数
Route::rule('middle2', 'Index/eee')
    ->middleware('auth', 'check');
// middleware不使用数组，从pagram开始的参数会变成额外参数
Route::rule('middle1/:name', 'Index/eee')
    ->middleware(['auth', 'check'], 'pagram', 'goodboy');
// 使用变量尖括号<>。
// Route::rule('rt/<id>', 'Index/aaaaa');
// 使用变量冒号:。可以做动态路由，确保存在:name方法
// Route::rule('rt/:name$', 'Index/:name');
// 严格匹配
// Route::get('rt/<id>$', 'Index/aaaaa');
// Route::get('rt/:id$', 'Index/aaaaa');
// 可选变量|路由标识
// Route::get('rt/[:id]/:nm', 'Index/aaaaa')
//     ->name('idrm') 
//     ->append(['abc'=>123, 'edf'=>456]);
// 可选变量|路由标识|必选变量
// 可选参数只能放到路由规则的最后，如果在中间使用了可选参数的话，后面的变量都会变成可选参数。官方文档不准确，实测必选变量:nm$必须传,url举例->优先传必选变量nm=9：http://127.0.0.1:8000/rt1/9.html。url举例->可选变量id=9,必选变量nm=23,http://127.0.0.1:8000/rt1/9/23.html。报错->http://127.0.0.1:8000/rt1.html。
Route::get('rt1/<id>', 'Index/aaaaa');
  //  ->name('idrm1') // 路由标识，使用url()函数调用：
    // url('idrm1', ['id'=>7555, 'nm'=>3613], true, true) 或者
    // url('Index/aaaaa', ['id'=>7555, 'nm'=>3613], true, true)。
  //  ->append(['abc'=>123, 'edf'=>456]);

// 任何类的任何方法，不限于控制器类。
Route::rule('extc', '\app\\extend\Cont@ccccc');
// 此处大坑，错误写法。不支持url访问控制器以外的类，只能路由访问，参照上一行
// Route::redirect('dict', '\app\\extend\Cont@ccccc', 302);
// 重定向是个大坑，只替换路由表达式/后边的内容
Route::redirect('Index/aaaaa', 'ccc', 302); //实际访问的是Index/ccc
Route::redirect('dict', 'Index\aaaaa'); //实际访问的是Index/aaaaa

// 注意是路由直接输出模板，不经过控制器。模板文件中可以直接输出当前请求的param变量,官方文档这句有误。
Route::view('vi/<names>', 'index/copy', ['aaaaa'=>'【aaaaa】44455'], 'GET'); //names报错，按官方流程打印都能正常输出，无法查明原因。
// 感觉用处不大，只有简单静态页的官网可以用一下。
Route::view('vv/cp', 'index/copy', ['names'=>'【name】路由到视图','aaaaa'=>'【aaaaa】1234567890']); //正确

// 路由到闭包，控制器和模板都省了.参数传递$name
// 不知道有什么应用场景
Route::get('abcd/:name', function($name)
{
    return '不错'.$name;
});
// 路由到依赖注入，控制器和模板都省了.参数传递$name
// 不知道有什么应用场景
Route::get('dcba/:name', function(Request $request, $name)
{
    $method = $request->method();
    return '['.$method.'],'.$name;
});

// 路由分组
/**
 * 第一个路由分组测试:mygroup分组
 */
// Route::group('mygroup', function()
// {
//     Route::rule('test1', 'Test/demo1');
//     Route::rule('test2', 'Test/demo2');
// });

/**
 * 第2个路由分组测试，yourgroup分组
 */
Route::group('yourgroup', function()
{
    Route::rule('test1', 'Test/demo3');
    Route::rule('test2/<id>', 'Test/demo4');
});

/**
 * 虚拟分组：测试分组名称可以不一致的情况
 * 官方文档：仅仅是用于对一些路由规则设置一些公共的路由参数。本例仅限.so后缀访问。
 * 正确访问:http://127.0.0.1:8000/mygroup/test1.so 和 http://127.0.0.1:8000/yourgroup/test1.so
 * 失败的访问:http://127.0.0.1:8000/mygroup/test1.html 和 http://127.0.0.1:8000/yourgroup/test1.html
 */
// Route::group(function()
// {
//     Route::rule('mygroup/test1', 'Test/demo1');
//     Route::rule('yourgroup/test1', 'Test/demo3');
// })->ext('so');

/**
 * 官方文档：如果使用了嵌套分组的情况，子分组会继承父分组的参数和变量规则，而最终的路由规则里面定义的参数和变量规则为最优先。
 * 23:56，有点儿困了，下次再测试。
 * wood测试：相同变量不同参数子分组（append(['group_id'=>801]）覆盖父分组（append(['group_id'=>5000]），取子分组（append(['group_id'=>801]）。
 * wood测试：同时设置伪静态后缀，父分组ext('so')和子分组ext('html|so')，取交集ext('so')。
 */
// mygroup/test1只支持.html访问；mygroup/test2只支持.so访问；group_id=801。
Route::group(function()
{
    Route::group('mygroup', function()
    {
        Route::rule('test1', 'demo1')->ext('html');
        Route::rule('test2', 'demo2')->ext('so');
    })->ext('html|so')->append(['group_id'=>802])->prefix('Test/');
})->append(['group_id'=>5000]);

/**
 * 资源路由:到控制器（官方：系统会自动注册7个路由规则），支持嵌套（猜测是可以把【自动注册7个路由规则】分到多个类中定义，对应方法read、edit等）。
 * 官方文档：用处不大，暂时不做过多测试了。
 * wood测试：注意请求类型不同（GET、POST、PUT等）和参数名称限制（id）。
 * @Author WoodSmoke
 * @Time 2022-08-18 10:13
 */
Route::resource('test', 'Test');

/**
 * 路由绑定：可以理解成动态路由，但是不用写路由规则（路由规则是Route::rule()的第一个参数）
 * 官方文档：把当前的URL绑定到控制器/操作，最多支持绑定到操作级别。当前的URL其实是所有URL
 * wood测试：不需要定义路由规则，bind方法填写域名才有效（第2个参数）；
 * wood测试：但是如果定义了路由规则，乖乖的用路由规则访问。上边的资源路由貌似与路由绑定不冲突。
 */

// 访问：http://127.0.0.1:8000/read/id/6
// 获取域名domain：$_SERVER['REMOTE_ADDR']
// 注意,绑定将导致路由访问Test控制器
// Route::bind('Test', '127.0.0.1');

// Route::rule('test/<id>', 'Test/read', 'GET');
// 访问：http://127.0.0.1:8000/id/6
// Route::bind('Test/read', '127.0.0.1');
// Route::bind('Test/demo5');

/**
 * 域名路由：容易理解，暂时不做测试
 * 官方文档：域名路由本身也是一个路由分组
 */

/**
 * 跨域请求：容易理解，暂时不做测试
 * 官方文档：allowCrossDomain
 */

 /**
  * 在这里使用的意义不大，去函数里生成
  * 官方文档：定义了路由规则，可以使用路由规则生成URL地址
  * 官方文档：如果没有指定路由标识，使用路由地址生成URL地址
  * 官方文档：如果注册路由的时候指定了路由标识，必须使用路由标识来生成URL地址[官方这句话貌似不对,经测试,如果定义了标识,不使用标识生成也可以]
  * URL生成:buildUrl()
  */

// 支持GET访问：http://127.0.0.1:8000/test4/100.html
Route::rule('test4/<id>', 'Test/demo4')->name('test_demo4');
// Route::buildUrl('Test_demo4',['id'=>100, 'demo'=>4]);
// 支持GET访问：http://127.0.0.1:8000/yourgroup/test2/100.html
// wood测试：如果定义了路由标识，注释掉yourgroup分组test2路由规则，URL
// Route::buildUrl('Test/demo4',['id'=>100, 'demo'=>4]);
// 域名的http://和端口会自动添加,锚点放在域名前面
// Route::buildUrl('yourgroup/test2/<id>#aaa@127.0.0.1');

// 测试Miss路由和Error空控制器优先级
// Wood测试,Miss路由优先
Route::rule('test40/<id>', 'Test/demo40')->name('test_demo40');

Route::rule('control/<id>', 'Test/control');

Route::rule('req/<id>', 'Copy/request_param');
// 1.如果伪装请求类型，系统会判断伪装的类型是不是和路由请求类型一致，而不是使用真实请求类型判断。
// 2.你可以设置为任何合法的请求类型，包括GET、POST、PUT和DELETE等，但伪装变量_method只能通过POST请求进行提交。对官方文档测试，如果不是post请求，确实伪装失效。
Route::rule('uest/<id>', 'Copy/request_test/<id>', 'post');

// 测试：参数绑定，3种写法都可以。
// 请规范使用路由表达式和路由地址，参考官方文档
// 传正确的参数（比如第1个路由表达式method传archive，第2个路由表达式method可以随便传），否则找不到控制器的方法。
// 要使用Cache，先在配置中（参考官方文档：全局请求缓存）开启缓存。
Route::rule('arch/<method>/<id>', 'Copy/<method>', 'GET')->append(['page'=>2])->cache(['arch/<method>/<id>/<page>', 10, 'page']);
// Route::rule('arch/<method>/<id>', 'Copy/archive');
// Route::rule('arch/archive/<id>', 'Copy/archive');
Route::rule('response/param', 'Copy/resparam', 'get');
Route::rule('getuser/:id', 'Copy/getUser', 'get');
// 测试获取器 2023-2-14
Route::rule('art/:id', 'Mydoc/getArticle', 'get');



