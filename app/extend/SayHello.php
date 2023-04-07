<?php 
/*
 * @Author: error: git config user.name && git config user.email & please set dead value or install git
 * @Date: 2022-05-27 11:11:07
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-04-07 12:11:16
 * @FilePath: /woodsmoke/app/extend/SayHello.php
 * @Description: 
 * 
 * Copyright (c) 2022 by error: git config user.name && git config user.email & please set dead value or install git, All Rights Reserved. 
 */
namespace app\extend;

trait SayHello
{
    public function SayHello(){
        parent::SayHello(); // 仅为测试代码，use trait的类A必须有真实继承(class A extends B)的时候才能使用parent
        echo "app\\extend\SayHello".PHP_EOL;
    }
}