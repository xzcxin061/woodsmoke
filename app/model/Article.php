<?php 
/*
 * @Author: error: error: git config user.name & please set dead value or install git && error: git config user.email & please set dead value or install git & please set dead value or install git
 * @Date: 2023-02-14 16:17:34
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-02-15 15:45:49
 * @FilePath: /woodsmoke/app/model/Article.php
 * @Description: 
 * 
 * Copyright (c) 2023 by ${git_name_email}, All Rights Reserved. 
 */
namespace app\model;

use think\Model;
use think\facade\Db;

class Article extends Model
{
    // 设置连接2
    protected $connection = "mysql";

    /**
     * 获取链接列表
     * @Author WoodSmoke
     */
    public function getArticleUrlAttr($value, $data)
    {
        $arr = [];
        foreach($data as $key=>$val)
        {
            if($val['article_url'] == $value)
            {
                array_merge($arr, $value);
            }
        }
        return $arr;
        
    }
}