<?php 
/*
 * @Author: chuiyan 
 * @Date: 2022-05-23 15:29:06
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-04-27 15:47:04
 * @FilePath: /woodsmoke/app/controller/Stock.php
 * @Description: 
 * 
 * Copyright (c) 2023 by ${git_name_email}, All Rights Reserved. 
 */

namespace app\controller;

use app\model\StockDaily;

class Stock
{
    /**
     * @Func 股票投资组合最大回撤
     * @Describe 导出图表和数据，也可能会拆分该函数
     * @Range 2021年7月20日-2023年3月20
     * @Time 2023/4/27
     * @Author WoodSmoke
     */
    public function maximumDrawdown(StockDaily $stockDaily)
    {
        
    }
}