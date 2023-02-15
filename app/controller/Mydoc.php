<?php 
/*
 * @Author: error: error: git config user.name & please set dead value or install git && error: git config user.email & please set dead value or install git & please set dead value or install git
 * @Date: 2022-05-23 15:29:06
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-02-15 17:14:02
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

    public function getArticle(Article $article)
    {
        // 貌似使用获取器只能查询一条数据
        $art = $article::find(260);
        // 通过获取器获取数据， 获取器有可能输出了改变【相比原始数据，获取器第二个参数$data】的数据
        $data = $art->article_url;
        // 通过getData获取原始数据，这里不能用$article， 要用$art
        var_dump($art->getData());
    }
}