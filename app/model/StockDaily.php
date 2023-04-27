<?php 
/*
 * @Author: error: error: git config user.name & please set dead value or install git && error: git config user.email & please set dead value or install git & please set dead value or install git
 * @Date: 2023-02-14 16:17:34
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-04-27 12:24:40
 * @FilePath: /woodsmoke/app/model/StockDaily.php
 * @Description: 
 * 
 * Copyright (c) 2023 by xzcxin061@163.com, All Rights Reserved. 
 */
namespace app\model;

use think\Model;
use think\facade\Db;
use think\facade\Config;
use think\Exception;
class StockDaily extends Model
{
    // 设置连接2
    protected $connection = "stock";
    // 设置额外添加字段
    // protected $append = ['title_length'];
    // 本地端口转发，在本地命令行执行
    // 第一种：ssh -fN -L3307:47.92.197.118:3307 xzc@47.92.197.118
    // stock连接使用第二种，13308是数据库服务端口----需要远程设置，3306是本地端口--可以是本地任意不冲突端口值，xzc是账户，47.92.197.118是ssh服务器远程ip，ssh端口使用默认值22，执行命令输入密码xzc20200718：
    // ssh -fNg -L 13308:127.0.0.1:3306 xzc@47.92.197.118

    // 命令行连接数据库：mysql -h 127.0.0.1 -P 13308 -uxzc -p xzc654321
    /**
     * 执行操作
     * @param string $cmd 执行的命令
     * @param array $config 连接配置文件, 具体参考上边的 $config 公共变量
     * @return mixed
     */
    public static function init2($cmd='', $config=[])
    {
        // $config = Config::get('database.connections.stock');
        
        // // 连接ssh服务器
        // $testConnection = \ssh2_connect('47.92.197.118', 22);
        // // 身份验证
        // \ssh2_auth_password($testConnection, 'xzc', 'xzc20200718');

        // $tunnel = \ssh2_tunnel($testConnection, $config['hostname'], '3306');

        // 以下代码无效，因为未开启mysqli扩展
        // $db = new \mysqli_connect(
        //     $config['hostname'], 
        //     $config['username'], 
        //     $config['password'], 
        //     $config['database'], 
        //     $config['hostport'], 
        //     $tunnel
        // );
        // // $connect = "mysql://xzc@xzc654321@127.0.0.1:13307/stock";
        // var_dump(Db::connect($db));
    }
}