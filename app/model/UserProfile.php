<?php 
/*
 * @Author: chuiyan xzcxin061@163.com
 * @Date: 2023-04-13 11:19:17
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2024-03-08 17:51:42
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

    /**
     * 关联查询支持获取器，绑定到父模型的字段也支持
     * 这是获取器在子模型中设置的例子
     * 父模型中设置的例子见getAddressAttr
     */
    public function getEmailAttr($value, $data)
    {
        $value = $value.".cn";
        return $value;
    }
    
}