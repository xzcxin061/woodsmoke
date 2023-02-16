<?php 
/*
 * @Author: error: error: git config user.name & please set dead value or install git && error: git config user.email & please set dead value or install git & please set dead value or install git
 * @Date: 2022-05-23 15:29:06
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-02-16 16:33:21
 * @FilePath: /woodsmoke/app/controller/Mydoc.php
 * @Description: 
 * 
 * Copyright (c) 2023 by ${git_name_email}, All Rights Reserved. 
 */

namespace app\controller;

// use app\controller\Copy;
use app\model\Article;

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
        // var_dump($art->getData('article_url'));echo "<br/>";echo "<br/>";
        // 显示的调用getAttr方法自动出发获取器getArticleUrlAttr
        // var_dump($art->getAttr('article_url'));echo "<br/>";echo "<br/>";

        // 获取器定义数据表不存在的字段
        // $title = $art->append(['title_length']);
        $title_length = $art->title_length;
        // var_dump($art);
    }
}