<?php 
/*
 * @Author: error: error: git config user.name & please set dead value or install git && error: git config user.email & please set dead value or install git & please set dead value or install git
 * @Date: 2022-06-30 11:58:45
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-04-17 12:06:31
 * @FilePath: /woodsmoke/app/model/User.php
 * @Description: 
 * 
 * Copyright (c) 2023 by ${git_name_email}, All Rights Reserved. 
 */
namespace app\model;

use think\Model;
use think\facade\Db;

class User extends Model
{
    // 设置连接2
    protected $connection = "mysql";
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
        // echo "【app\model\User】"; echo "通过路由绑定事件，模型自动注入。";echo "<br/>";
    }

    // public static function __make(Query $query)
    // {
    //     return (new self())->setQuery($query);
    // }
    /**
     * 获取用户列表，获取多个用户使用
     * @Author WoodSmoke
     * @param num 单页条数
     */
    public static function getUsers($num = 1)
    {
        // 官方文档：thinkORM所有查询都采用静态方法，ORM的数据管理类是think\DbManager,实际使用对应的门面类think\facade\Db。DbManager先通过__call()实例化了connection类，并调用connection类的table()方法，然后再调用DbManager类的table()方法进行设置，【流程很复杂】。
        // 数据库查询：查询单个数据不存在返回空数组，返回模型和返回数组是模型查询跟数据库查询的区别
        $list = Db::table('user')->where('status', 0)->findOrEmpty(); // 返回数组
        // $list = Db::table('user')->where('status', 1)->find(); // 空数据返回null
        echo <<<'EOF'
        打印来自模型内部【var_dump(Empty($list)); 】：<br/>
        EOF;
        var_dump(Empty($list));
    }

    /**
     * 
     */
    public function article()
    {
        // 关联模型（必须）：关联模型类名
        // 外键：关联模型外键，默认的外键名规则是当前模型名+_id
        // 主键：当前模型主键，一般会自动获取也可以指定传入
        // return $this->hasMany(Article::class, 'user_id', 'id'); // Article和User在同级目录，不需要使用use
        // 关联模型（必须）：关联模型类名
        // 外键：默认的外键规则是当前模型名（不含命名空间，下同）+_id ，例如user_id
        // 主键：当前模型主键，默认会自动获取也可以指定传入
        return $this->hasOne(Article::class, 'user_id', 'id'); // 这里官方文档没有说明取谁的外键和主键，外键取子表article的外键user_id，主键取主表user的主键id
    }
}