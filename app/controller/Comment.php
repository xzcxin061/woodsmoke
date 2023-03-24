<?php
/*
 * @Author: chuiyan xzcxin061@163.com
 * @Date: 2023-03-13 14:33:10
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-03-24 19:32:01
 * @FilePath: /woodsmoke/app/controller/Comment.php
 * @Description: 
 * 
 * Copyright (c) 2023 by ${user.name}/{user.email}, All Rights Reserved. 
 */

namespace app\controller;

header('Content-Type: text/html; charset=utf-8');
class Comment{
    /**
     * 反编译
     * 原代码只支持thinkphp5,改写成依赖注入方法
     */
    public function MakeDict(){
        /**
         * 插件Demo
         * 源码开始：
         * header('Content-Type: text/html; charset=utf-8');
         * require_once('phpanalysis.class.php');
         * $pa = new PhpAnalysis('utf-8', 'utf-8', false);
         * $pa->MakeDict( sourcefile,  16 , 'dict/base_dic_full.dic');
         * echo "OK";
         * 源码结束。
         */
        // 数据可以从数据库查询，这里不写demo了。
        $str = "直奔这里来的，路途有点坎坷，但是窝窝确实好，这里的人也非常不错，还让我们洗澡，外面的窝窝免费停，有水，有卫生间，24小时开放，要去景区里面就二十元，可以在里面过夜的，不过我们没去，一个字好，";
        // 命名空间从thinkphp的extend子目录开始写。只支持传入字符串，如果在这里传入字符串调用每次都要初始化，最好改写一下WordAnalysis\PhpAnalysis.php\SetSource方法支持传入字符串、数组、对象、数据集或者匿名函数
        $pa = invoke('WordAnalysis\PhpAnalysis', ['utf-8', 'utf-8', false, $str]);
        // 路径要加上extend,需要php.ini设置足够大内存memeroy_limit
        $pa->MakeDict('extend/WordAnalysis/dict/not-build/base_dic_full.txt',  16 , 'extend/WordAnalysis/dict/base_dic_full.dic');
        // echo "OK";
        // phpinfo();
        $pa->StartAnalysis(true);
        $result = $pa->GetFinallyResult();
        // 测试分词效果不是很理想，粗算准确率不高于73.33%
        var_dump($result);
    }
}



