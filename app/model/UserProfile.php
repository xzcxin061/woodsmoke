<?php 
/*
 * @Author: chuiyan xzcxin061@163.com
 * @Date: 2023-04-13 11:19:17
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2024-03-06 16:27:36
 * @FilePath: /woodsmoke/app/model/UserProfile.php
 * @Description: 一对一关联
 * 
 * Copyright (c) 2023 by ${user.name}/{user.email}, All Rights Reserved. 
 */
// 命名空间
namespace app\model;
// 加载类
use think\Model;

class UserProfile extends Model
{
    // public function __construct()
    // {
    //     # code...
    //     // var_dump($this);
    //     // $a = static::class;
    //     // var_dump(new $a());
    //     // var_dump(new static());
    // }

    /**
     * 定义一个相对关联，参数如下：
     * 关联模型（必须）：关联模型类名
     * 外键：当前模型外键，默认的外键名规则是关联模型名+_id
     * 关联主键：关联模型主键，一般会自动获取也可以指定传入
     */
    public function user()
    {
        // 表字段符合系统命名规则的时候，第二个和第三个参数不用传
        return $this->belongsTo(User::class);
    }
    
}