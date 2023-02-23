<?php 
/*
 * @Author: error: error: git config user.name & please set dead value or install git && error: git config user.email & please set dead value or install git & please set dead value or install git
 * @Date: 2022-05-23 15:29:06
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-02-22 19:05:02
 * @FilePath: /woodsmoke/app/controller/Mydoc.php
 * @Description: 
 * 
 * Copyright (c) 2023 by ${git_name_email}, All Rights Reserved. 
 */

namespace app\controller;

// use app\controller\Copy;
use app\model\Article;
use think\facade\Db;

class Mydoc
{
    public function firstapp()
    {
        $a = invoke('app\controller\Copy');
        // echo $a;
        return $a->mytest();
    }

    public function secondapp(Copy $a)
    {


        echo $a->mytest();
    }


    /**
     * 待测试:
     * append方法的作用;
     * 模型的数据对象取值操作,没有定义获取器,也触发了获取器;
     * 模型序列化输出触发获取器
     */
    public function getArticle(Article $article)
    {
        // 貌似使用获取器只能查询一条数据
        $art = $article->find(260);
        // 通过获取器获取数据(前提是在Article模型定义了获取器)
        $url = $art->article_url?:'';
        // 没有定义获取器也可以获取数据,$art本身就是传入参数的模型实例化对象.
        $fabutime = $art->fabutime?:'';
        // var_dump($url);echo "<br/>";echo "<br/>";
        // 通过getData获取原始数据，这里不能用$article【因为它是模型初始化的对象(模型实例)，里边没有数据，不理解看官方文档依赖注入】， 要用$art
        // echo "【getData】";var_dump($art->getData('article_url'));echo "<br/>";echo "<br/>";
        // 获取原始数据建议用getOrigin代替使用getData，参考ThinkORM。字段类型和时间字段的自动处理不再纳入获取器范畴，使用getData获取时间字段的原始值，需要关闭自动时间字段处理功能，设置autoWriteTimestamp属性为false。希望时间字段自动写入处理，但不希望进行自动格式化输出，可以设置dateFormat属性为false。
        // 在数据库配置文件中设置的话,自动写入时间戳：'auto_timestamp' = true,时间字段取出后默认时间格式：'datatime_format' = false.
        // echo "【getOrigin】";var_dump($art->getOrigin('article_url'));echo "<br/>";echo "<br/>";
        
        // 显示的调用getAttr方法自动出发获取器getArticleUrlAttr
        // var_dump($art->getAttr('article_url'));echo "<br/>";echo "<br/>";

        // 获取器定义数据表不存在的字段
        $title_length = $art->title_length;
        // var_dump($art); // 返回3个带数据的属性，data,origin,get,其中只有get属性包含触发获取器添加的数据表没有的字段
        // 模型的序列化输出,append(['title_length'])(貌似优先)，也可以在模型里设置$append=['title_length'],二选一
        // toArray()不用append，就不会包括数据表没有的字段。
        $arr = $art->append(['title_length'])->toArray();
        // var_dump($arr);

        // 实测模型查询find()/select()一定要放在动态获取器withAttr()前边，否则不走动态获取器。Db查询find()/select()必须写withAttr()后边。
        // 官方文档：如果同时还在模型里面定义了相同字段的获取器，则动态获取器优先，经测试是的。
        // withAttr方法支持多次调用，定义多个字段的获取器。
        $array = $article->limit(3)->select()->withAttr('article_url', function($value, $data){
            return $value;
        })->withAttr('id', function($value, $data){
            return $value."~~~~";
        });
        // 不通过获取器也能获取，获取器是为了特殊处理
        foreach($array as $key=>$val){
            var_dump($array[$key]->article_url.$key);echo "<br/>";echo "<br/>";
            var_dump($array[$key]->id.$key);echo "<br/>";echo "<br/>";
        }
        // 另外注意，withAttr方法之后不能再使用模型的查询方法，必须使用Db类的查询方法。这种方式官方文档举的例子用模型查询坑人。
        // 实测代码，注意，返回的是数组，不是对象了。
        $array1 = Db::table('article')->withAttr('article_url', function($value, $data){
            return $value."----";
        })->find(280);
        // var_dump($array1);
        var_dump($array1['article_url']);
    }
}