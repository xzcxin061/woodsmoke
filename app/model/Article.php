<?php 
/*
 * @Author: error: error: git config user.name & please set dead value or install git && error: git config user.email & please set dead value or install git & please set dead value or install git
 * @Date: 2023-02-14 16:17:34
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-04-17 12:12:03
 * @FilePath: /woodsmoke/app/model/Article.php
 * @Description: 
 * 
 * Copyright (c) 2023 by ${git_name_email}, All Rights Reserved. 
 */
namespace app\model;

use think\Model;
use think\facade\Db;
use think\model\concern\SoftDelete; // 类外部导入命名空间
// use app\model\User;

class Article extends Model
{
    // 设置软删除字段
    use SoftDelete; // 类内部 use trait
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;
    // 设置连接2
    protected $connection = "mysql";
    // protected $append = ['title_length'];
    
    /**
     * @Refer 获取器，数据表字段article_url
     * @Author WoodSmoke
     * @Param $value article表主键id对应的article_url字段数据.系统实现(不用考虑怎么实现的)
     * @Param $data 原始数据,article表主键id对应的一条完整数据，系统实现(不用考虑怎么实现的).原始数据不会被改变.
     * @Func 官方文档说必须是public,测试protected也没影响
     * @Note 避坑：如果数据表字段带下划线，定义获取器时，下划线要进行驼峰转换。
     */
    protected function getArticleUrlAttr($value, $data)
    {
        // 其实这里可以不用做任何处理，模型的实例化对象就可以输出原始数据,除非你想改变输出数据,并且在应用中主动触发获取器.
        $url = $value."11111.html";
        // return $url;
        $arr = array_merge($data, ['change_url' => $url]); // 构造新的数据,你可以随心所欲
        return $arr; // 这里返回什么,获取器($model->article_url)获取的就是什么
    }

    /**
     * @Refer 获取器，不存在的数据表字段
     * @Author Woodsmoke
     * @Param $value, $data
     * @Func 官方文档说必须是public,测试protected也没影响
     */
    protected function getTitleLengthAttr($value, $data)
    {
        $title = $data['title'];
        $length = mb_strlen($title, 'UTF-8');
        
        return $length;
    }

    /**
     * 修改器
     * @Author woodsmoke
     * @Time 2023-3-3
     */
    protected function setUidAttr($value, $data)
    {
        return strval($value + 1);
    }

    /**
     * 搜索器uid
     * 限制和规范表单的搜索条件；
     * 预定义查询条件简化查询；（一般不是指MySQL，您可以使用MySQL中的过程（或可能是函数）来完成此操作：http://dev.mysql.com/doc/refman/5.1/en/create-procedure.html或http://dev.mysql.com/doc/refman/5.1/en/stored-routines.html）
     * @Author woodsmoke
     * @Time 2023-3-3
     */
    public function searchTitleAttr($query, $value, $data)
    {
        $query->where('title','like', $value.'%');
    }

    /**
     * 搜索器article_url
     * 定义多个搜索器一起使用
     * @Author woodsmoke
     * @Time 2023-3-3
     */
    public function searchUidAttr($query, $value, $data)
    {
        $query->where('uid','=', $value);
    }

    public function user()
    {
        // 关联模型（必须）：关联模型类名
        // 外键：默认的外键规则是当前模型名（不含命名空间，下同）+_id ，例如user_id
        // 主键：当前模型主键，默认会自动获取也可以指定传入
        return $this->hasOne(User::class, 'id', 'user_id'); // 这里官方文档没有说明取谁的外键，测试主键取主表article的外键user_id，外键取子表user的主键id，这是由数据表结构决定的，虽然不建议用反转主表和子表的写法。
    }
}