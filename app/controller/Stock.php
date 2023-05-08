<?php 
/*
 * @Author: chuiyan 
 * @Date: 2022-05-23 15:29:06
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-05-08 19:00:12
 * @FilePath: /woodsmoke/app/controller/Stock.php
 * @Description: 
 * 
 * Copyright (c) 2023 by ${git_name_email}, All Rights Reserved. 
 */

namespace app\controller;

use app\model\StockDaily;

class Stock
{
    // 选择投资组合，数组和字符串二选一
    protected $stockCodeArr = ['002228', '002345', '600016', '600668', '601186', '601336'];
    protected $stockCodeStr = '002228,002345,600016,600668,601186,601336';
    // 投资期限-最大回撤
    protected $startTime = '210720';
    protected $stopTime = '230320';

    protected $resultArr = [];
    protected $finalArr = [];
    protected $handleFinalArr = [];

    // 601319
    // 601688
    // 600623
    // 600810
    // 600475
    // 600585
    // 600104
    // 600997
    // 000725
    // 600028
    // 600398
    // 600308
    // 600782
    // 600000



    // 原始投资组合，参数：日期，股数，价格：[code, date, shares, value]
    // protected $originBuyArr = [
    //                                 ['002228', '20210720', 2000, 3.60], 
    //                                 ['002228', '20210720', 3000, 3.54], 
    //                                 ['002345', '20210720', 3000, 5.85],
    //                                 ['600016', '20210720', 2000, 4.17],
    //                                 ['600668', '20210720', 1000, 11.14],
    //                                 ['601186', '20210720', 5000, 7.29],
    //                                 ['601336', '20210720', 1500, 44.48]
    //                             ]; 
    // 原始投资组合，最小为二维数组。参数：日期，股数，均价：[code, date, shares, value]
    protected $handleBuyArr = [
                                '002228' => ['002228', '20210720', 5000, 3.564], 
                                '002345' => ['002345', '20210720', 3000, 5.85],
                                '600016' => ['600016', '20210720', 2000, 4.17],
                                '600668' => ['600668', '20210720', 1000, 11.14],
                                '601186' => ['601186', '20210720', 5000, 7.29],
                                '601336' => ['601336', '20210720', 1500, 44.48]
    ];     
    // 买入记录，最小为三维数组。参数：日期，股数，均价：[code, date, shares, value]
    protected $buyArr = [
                            '002228' => [['002228', '20220622', 4000, 3.41]],
                        ];
    // 卖出记录，最小为三维数组。参数：日期，股数，均价：[code, date, shares, value]
    protected $saleArr = [
                            '002228' => [['002228', '20221011', -6000, 3.10]],
                            '002345' => [['002345', '20230213', -1500, 6.55]]
                        ];
    
    /**
     * @Func 股票投资组合最大回撤率：在选定周期内任一历史时点往后推，产品净值走到最低点时的收益率回撤幅度的最大值。
     * @Describe 导出图表和数据，也可能会拆分该函数
     * @Range 2021年7月20日-2023年3月20
     * @Time 2023/4/27
     * @Author WoodSmoke
     */
    public function maximumDrawdown ()
    {
        // 获取指定股票收盘价，@return 包含模型的数据集
        $data = StockDaily::withSearch( ['date', 'stockcode'], 
                                        ['date'=>[$this->startTime, $this->stopTime], 'stockcode'=>$this->stockCodeStr]
                            )
                        ->field('date, stockcode, close')
                        ->select();
        // $resultArr数组赋值
        if (!$data->isEmpty()) {
            // 先不考虑添加原始投资组合之外的股票stockcode
            foreach ($data AS &$val) {
                // x轴：时间
                $this->resultArr[$val->stockcode]['x'][$val->date] = $val->date;
                // y轴：收盘价
                $this->resultArr[$val->stockcode]['y'][$val->date] = $val->close;

                // 计算每天的持有股数
                $this->resultArr[$val->stockcode]['shares'][$val->date] = $this->handleBuyArr[$val->stockcode][2];
                // 处理买入数据，2021年7月20日-2023年3月20，每天
                if (!empty(array_filter($this->buyArr)) && !empty($this->buyArr[$val->stockcode])) {
                    foreach ($this->buyArr[$val->stockcode] as $k => $v) {
                        if ($v[1] <= $val->date) {
                            $this->resultArr[$val->stockcode]['shares'][$val->date] += $v[2];
                        } else {
                            $this->resultArr[$val->stockcode]['shares'][$val->date] += 0;
                        }
                    }
                }
                // 处理卖出数据，2021年7月20日-2023年3月20，每天
                if (!empty(array_filter($this->saleArr)) && !empty($this->saleArr[$val->stockcode])) {
                    foreach ($this->saleArr[$val->stockcode] as $k => $v) {
                        if ($v[1] <= $val->date) {
                            $this->resultArr[$val->stockcode]['shares'][$val->date] += $v[2];
                        } else {
                            $this->resultArr[$val->stockcode]['shares'][$val->date] += 0;
                        }
                    }
                }
            }
        }
        
        // 股票收益率 = 收益额 / 原始投资额
        // 分析要点：
            // 单只股票指定日期前最后一次买入或者卖出的日期
            // 原始投资额 = 均价 * 持有股数
            // 均价 = (持有股数1 * 买入价格1 + ... + 持有股数n * 买入价格n) / (持有股数1 + ... + 持有股数n)
            // 收益额 = 原始投资额 - 指定日期收盘持有价值 + 累计盈亏
        if (!empty(array_filter($this->resultArr))) {
            foreach ($this->resultArr as $key => &$value) {
                $orginAmount = 0.00; // 原始投资额
                $originPriceAverage = $this->handleBuyArr[$key][3]; // 原始均价
                foreach ($value['x'] as $k => $v) {
                    // 设置均价每日默认值
                    $this->resultArr[$key]['junjia'][$v] = $originPriceAverage;
                    // 收盘额，2021年7月20日-2023年3月20，按每天持有股数统计。
                    $value['shoupane'][$v] = $value['shares'][$v] * $value['y'][$v];
                    // 计算均价,需要判断股票code存在
                    // 处理买入数据，2021年7月20日-2023年3月20，每天
                    if (!empty(array_filter($this->buyArr)) && !empty($this->buyArr[$key])) {
                        foreach ($this->buyArr[$key] as $val) {
                            if ($val[1] <= $v) {
                                $this->resultArr[$key]['junjia'][$v] = round(
                                    ($originPriceAverage * $this->handleBuyArr[$key][2] + $val[3] * $val[2]) // 持有价值
                                    / 
                                    $value['shares'][$v] // 持有股数
                                    , 2
                                );
                            }
                        }
                    }
                    // 处理卖出数据，2021年7月20日-2023年3月20，每天
                    if (!empty(array_filter($this->saleArr)) && !empty($this->saleArr[$key])) {
                        foreach ($this->saleArr[$key] as $val) {
                            if ($val[1] <= $v) {
                                $this->resultArr[$key]['junjia'][$v] = round(
                                                                            ($this->resultArr[$key]['junjia'][$v] * $value['junjia'][$v] + $val[3] * $val[2]) // 持有价值
                                                                            / 
                                                                            ($value['junjia'][$v] + $val[2]) // 持有股数
                                                                       , 2
                                );
                            }
                        }
                    }

                }
                $this->resultArr[$key] = $value;
            }
        }

        // 分析思路
        // 
        if (!empty(array_filter($this->resultArr))) {
            $orginAmountOuter = 0.00;
            $shoupaneOuter = 0.00;
            $shouyieOuter = 0.00;
            $shouyilvOuter = 0.0000;            
            foreach ($this->resultArr as $key => &$value) {
                $yingkuieInner = 0.00;
                $shouyieInner = 0.00;
                $orginAmountInner = 0.00;
                foreach ($value['x'] as $k => $v) {
                    // 每天对应的原始投资额 = 每天的均价 * 每天的持有股数
                    $this->resultArr[$key]['orginAmount'][$v] = $value['junjia'][$v] * $value['shares'][$v];
                    // 每天的收盘额 = 每天的收盘价 * 每天的持有股数
                    $this->resultArr[$key]['shoupane'][$v] = $value['y'][$v] * $value['shares'][$v];
                    // 每天的收益额 = 每天的原始投资额 - 每天的收盘额 + 累计盈亏
                    $this->resultArr[$key]['shouyie'][$v] = round($this->resultArr[$key]['orginAmount'][$v] - $this->resultArr[$key]['shoupane'][$v]
                                                            , 2);
                    // 累计盈亏
                    if (!empty(array_filter($this->saleArr)) && !empty($this->saleArr[$key])) {
                        foreach ($this->saleArr[$key] as $val) {
                            if ($val[1] == $v) {
                                $yingkuieInner += $this->resultArr[$key]['orginAmount'][$v] - $this->resultArr[$key]['shoupane'][$v];                    
                            } 
                            $this->resultArr[$key]['yingkuie'][$v] = $yingkuieInner;
                        }
                    }

                    // 更新收益额
                    $this->resultArr[$key]['shouyie'][$v] = $this->resultArr[$key]['shouyie'][$v] + $yingkuieInner;
                    // 每天的收益率 = 每天的收益额 / 每天的原始投资额
                    $this->finalArr[$v][$key]['shouyie'] = $this->resultArr[$key]['shouyie'][$v];
                    $this->finalArr[$v][$key]['orginAmount'] = $this->resultArr[$key]['orginAmount'][$v];
                }

            }
        }
        if (!empty(array_filter($this->finalArr))) {
            foreach ($this->finalArr as $key => $value) {
                $shouyie = 0.00;
                $orginAmount = 0.00;
                $shouyilv = 0.0000;
                if (!empty(array_filter($value))) {
                    foreach ($value as $k => $v) {
                        $shouyie += $v['shouyie'];
                        $orginAmount += $v['orginAmount'];
                    }
                    $shouyilv = round($shouyie / $orginAmount, 4);
                }
                $this->handleFinalArr[$key]['shouyilv'] = $shouyilv;
            }
        }

        if (!empty($this->handleFinalArr)) {
            $size = sizeof($this->handleFinalArr);
            $keys = array_keys($this->handleFinalArr);
            $keysReverse = array_flip($keys);
            foreach ($this->handleFinalArr as $key => &$value) {
                if ($key == "20".$this->stopTime) {
                    $value['maxHuichelv'] = 0.0000;
                } else {
                    $childrenArr = array_slice($this->handleFinalArr, $keysReverse[$key] + 1, $size - ($keysReverse[$key] + 1), true);
                    $minShouyilv = min($childrenArr);
                    if ($value['shouyilv'] - $minShouyilv['shouyilv'] >= 0) {
                        $value['maxHuichelv'] = round($value['shouyilv'] - $minShouyilv['shouyilv'], 4);
                    } else {
                        $value['maxHuichelv'] = 0.0000;
                    }
                }
                $this->handleFinalArr[$key] = $value;
            }
        }        
        dump($this->handleFinalArr);
    }
}