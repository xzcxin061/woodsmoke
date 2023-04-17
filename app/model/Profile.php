<?php 
/*
 * @Author: chuiyan xzcxin061@163.com
 * @Date: 2023-04-13 11:19:17
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-04-14 15:38:46
 * @FilePath: /woodsmoke/app/model/Profile.php
 * @Description: 一对一关联
 * 
 * Copyright (c) 2023 by ${user.name}/{user.email}, All Rights Reserved. 
 */
// 命名空间
namespace app\model;
// 加载类
use think\Model;

class Profile extends Model
{
    public function __construct()
    {
        # code...
        // var_dump($this);
        $a = static::class;
        var_dump(new $a());
        // var_dump(new static());
    }
    
}