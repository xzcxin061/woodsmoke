<?php
/*
 * @Author: chuiyan xzcxin061@163.com
 * @Date: 2024-03-22 17:29:41
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2024-03-25 13:55:45
 * @FilePath: /woodsmoke/app/model/Comment.php
 * @Description: 评论模型
 * 
 * Copyright (c) 2024 by ${user.name}/{user.email}, All Rights Reserved. 
 */

namespace app\model;
use think\model;

class Comment extends Model
{
    public function articles()
    {
        return $this->belongsTo(Article::class);
    }
}