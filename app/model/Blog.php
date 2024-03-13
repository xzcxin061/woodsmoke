<?php 
/*
 * @Author: chuiyan xzcxin061@163.com
 * @Date: 2024-03-12 14:46:44
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2024-03-13 10:24:13
 * @FilePath: /woodsmoke/app/model/Blog.php
 * @Description: 
 * 
 * Copyright (c) 2024 by ${user.name}/{user.email}, All Rights Reserved. 
 */

namespace app\model;

use think\Model;

class Blog extends Model
{
    // 写点什么...
    public function content()
    {
        return $this->hasOne(Content::class);
    }
}