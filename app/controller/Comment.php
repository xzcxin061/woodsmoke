<?php
/*
 * @Author: chuiyan xzcxin061@163.com
 * @Date: 2023-03-13 14:33:10
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-03-24 18:55:13
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
         * 源码开始：
         * header('Content-Type: text/html; charset=utf-8');
         * require_once('phpanalysis.class.php');
         * $pa = new PhpAnalysis('utf-8', 'utf-8', false);
         * $pa->MakeDict( sourcefile,  16 , 'dict/base_dic_full.dic');
         * echo "OK";
         * 源码结束。
         */
        // 只支持传入字符串。数据可以从数据库查询，这里不写demo了。
        $str = "成都凡一房车，到此一游。感谢楼主感谢老板，这次老板免费充电，他家菜味道不错?";
        // 命名空间从thinkphp的extend子目录开始写
        $pa = invoke('WordAnalysis\PhpAnalysis', ['utf-8', 'utf-8', false, $str]);
        // 路径要加上extend,需要php.ini设置足够大内存memeroy_limit
        $pa->MakeDict('extend/WordAnalysis/dict/not-build/base_dic_full.txt',  16 , 'extend/WordAnalysis/dict/base_dic_full.dic');
        // echo "OK";
        // phpinfo();
        $pa->StartAnalysis(true);
        $result = $pa->GetFinallyResult();
        var_dump($result);
    }

    /**
     * 开始分析
     */
    public function StartAnalysis(){
        
    }
}



