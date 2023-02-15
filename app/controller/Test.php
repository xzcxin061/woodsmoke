<?php 
namespace app\controller;
use app\Request;

use app\BaseController;
use think\rule;
use think\facade\Cache;
class Test extends BaseController
{
  public function demo1()
  {
    echo "【app\controller\Test\demo1】";var_dump(request()->param('group_id'));echo "<br/>";
    echo "【app\controller\Test\demo1】";echo "mygroup分组1";echo "<br/>";
  }

  public function demo2()
  {
    echo "【app\controller\Test\demo2】";var_dump(request()->param('group_id'));echo "<br/>";
    echo "【app\controller\Test\demo1】";echo "mygroup分组2";echo "<br/>";
  }

  public function demo3()
  {
    echo "yourgroup分组1";
  }

  public function demo4($id)
  {
    echo "【app\controller\Test\demo4】";echo "yourgroup分组2";echo "<br/>";
    // 助手函数url()可以完成相同的功能
    // echo "【app\controller\Test\demo4】";echo \think\facade\Route::buildUrl('test_demo4@127.0.0.1', ['id'=>90]);echo "<br/>";
    echo "【app\controller\Test\demo4】";echo \think\facade\Route::buildUrl('Test/demo4', ['id'=>20])->domain(true);echo "<br/>";
  }

  public function read($id)
  {
    var_dump(request()->param('id'));
    echo "【app\controller\Test\\read】";echo "路由绑定121";echo "<br/>";
  }

  /**
   * 控制器测试
   * 由于设置了Miss路由(该操作会开启强制路由),注意设置路由才能访问
   */
  public function control(Request $request)
  {
    // echo "【app\controller\Test\control】";var_dump($request->action());echo "<br/>";
    // echo "【app\controller\Test\control】";var_dump($request->action(true));echo "<br/>";
    // echo "【app\controller\Test\control】";var_dump($request->controller());echo "<br/>";
    // echo "【app\controller\Test\control】";var_dump($request->controller(true));echo "<br/>";
    // echo "【app\controller\Test\control】";var_dump(parse_name($request->action()));echo "<br/>";
    // echo "【app\controller\Test\control】";var_dump($request->has('request', 'request'));echo "<br/>";

    // echo "【app\controller\Test\control->param】";var_dump($request->param());echo "<br/>";
    // echo "【app\controller\Test\control->get】";var_dump($request->get());echo "<br/>";
    // echo "【app\controller\Test\control->post】";var_dump($request->post());echo "<br/>";
    // echo "【app\controller\Test\control->put】";var_dump($request->put());echo "<br/>";

    echo "【app\controller\Test\control】";echo "<br/>";
    /** EOF的用法
     * 使用概述：
     * 1. 必须后接分号，否则编译通不过。
     * 2. EOF 可以用任意其它字符代替，只需保证结束标识与开始标识一致。
     * 3. 结束标识必须顶格独自占一行(即必须从行首开始，前后不能衔接任何空白和字符)。
     * 4. 开始标识可以不带引号或带单双引号，不带引号与带双引号效果一致，解释内嵌的变量和转义符号，带单引号则不解释内嵌的变量和转义符号。
     * 5. 当内容需要内嵌引号（单引号或双引号）时，不需要加转义符，本身对单双引号转义，此处相当与q和qq的用法。
     */
    echo <<<'EOF'
      【get打印内容】：var_dump($request->get('name', 'abc'))<br/>=>
    EOF;
    echo "【get打印结果】：";var_dump($request->get('name', 'abc'));echo "<br/>";
    echo <<<'EOF'
      【request打印内容】：var_dump($request->request('name', 'abc'))<br/>=>
    EOF;
    echo "【request打印结果】：";var_dump($request->request('name', 'abc'));echo "<br/>";
    echo <<<'EOF'
      【param打印内容】：var_dump($request->param('name', 'abc'))<br/>=>
    EOF;
    echo "【param打印结果】：";var_dump($request->param('name', 'abc'));echo "<br/>";
    // halt('输出测试:ddgge4');
  }

}