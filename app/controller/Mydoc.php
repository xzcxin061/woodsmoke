<?php 
/*
 * @Author: error: error: git config user.name & please set dead value or install git && error: git config user.email & please set dead value or install git & please set dead value or install git
 * @Date: 2022-05-23 15:29:06
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-03-02 19:32:54
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

        /**
         * 实测模型查询find()/select()一定要放在动态获取器withAttr()前边。Db查询find()/select()必须写在动态获取器withAttr()后边。
         * 官方文档：如果同时还在模型里面定义了相同字段的获取器，则动态获取器优先，经测试是的。
         * withAttr方法支持多次调用，定义多个字段的获取器。
         * $array返回的是一个对象
         */
        $array = $article->limit(3)->select()->withAttr('article_url', function($value, $data){
            return $value;
        })->withAttr('id', function($value, $data){
            return $value."~~~~";
        });
        // 不通过获取器也能获取，获取器是为了特殊处理
        foreach($array as $key=>$val){
            var_dump($val->article_url.$key);echo "<br/>";echo "<br/>";
            var_dump($val->id.$key);echo "<br/>";echo "<br/>";
        }
        // var_dump($array);

        /**
         * 下面这种方式不会报错，但是实际上没有经过动态获取器处理，是一个普通的模型查询
         * 注意$array返回数据和上边不一样
         */
        $array = $article->withAttr('article_url', function($value, $data){
            return $value;
        })->withAttr('id', function($value, $data){
            return $value."~~~~";
        })->limit(3)->select();
        // // 不通过获取器也能获取，获取器是为了特殊处理
        // var_dump($array);

        /**
         * 另外注意，withAttr方法之后不能再使用模型的查询方法，必须使用Db类的查询方法。
         * 实测代码，注意，Db查询返回的是数组，不是对象了。
         * 该注释存疑：这种方式官方文档举的例子用模型查询坑人。
         */
        $array1 = Db::table('article')->withAttr('article_url', function($value, $data){
            return $value."----";
        })->find(280);
        // // var_dump($array1);
        // var_dump($array1['article_url']);
    }

    /**
     * @Author Woodsmoke
     * @Description 测试修改器
     * @func 触发setUidAttr修改器
     */
    public function setArticle(Article $article0) 
    {
        // 写法1：静态查询方法
        // $article = Article::find(260); //原始 uid=2
        // $article->uid = $article->uid; // 赋值操作左边触发修改器
        // $article->save(); // 更新数据，入库

        // 写法2：依赖注入方法
        // $data = $article0->find(260);
        // $data->uid = 1; // 模型对象赋值,触发修改器
        // $data->save(); // 不要用$article0【初始化模型，空数据】调save()，用$data【模型数据对象】调才正确
        // var_dump($data->uid);

        // 写法3：调用模型的data方法，并且第二个参数传入true
        // $data1 = $article0->find(260);
        // $data1->data(['uid' => 1], true);
        // $data1->save(); // 这里save方法没有传入数据，所以不会触发修改器
        // var_dump($data1->uid);

        // 写法4：调用模型的appendData方法，并且第二个参数传入true
        // $data2 = $article0->find(260);
        // $data2->appendData(['uid' => 1], true);
        // $data2->save(); // 这里save方法没有传入数据，所以不会触发修改器
        // var_dump($data2->uid);

        // 写法5：调用模型的save方法，并且传入数据；
        // $data3 = $article0->find(260);
        // $data3->save(['uid' => 1]); // 这里save方法传入数据，会触发修改器
        // var_dump($data3->uid);

        // 写法6：显式调用模型的setAttr方法
        // $data4 = $article0->find(260);
        // $data4->setAttr('uid', 3, []); // 第三个数组参数，可根据需要传
        // var_dump($data4->uid);

        // 写法7：显式调用模型的setAttrs方法,效果与appendData并传入true的用法相同
        $data4 = $article0->find(260);
        $data4_array = $data4->toArray(); // 触发获取器
        $data4->setAttrs($data4_array); // 触发修改器，这里建议使用一维数组(name必须是字符串，一般对应表字段)，setAttrs循环里还是调用setAttr
        var_dump($data4->uid);

    }
}