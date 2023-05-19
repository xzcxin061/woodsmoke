<?php 
/*
 * @Author: chuiyan 
 * @Date: 2022-05-23 15:29:06
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-05-19 10:52:20
 * @FilePath: /woodsmoke/app/controller/Stock.php
 * @Description: 
 * 
 * Copyright (c) 2023 by ${git_name_email}, All Rights Reserved. 
 */

namespace app\controller;

use app\model\Daily;

class Stock
{
    // 选择投资组合1，数组和字符串二选一
    // protected $stockCodeArr = ['002228', '002345', '600016', '600668', '601186', '601336'];
    // protected $stockCodeStr = '002228,002345,600016,600668,601186,601336';
    // 投资期限-最大回撤
    // protected $startTime = '210720';
    // protected $stopTime = '230320';
    // 原始投资组合，最小为二维数组。参数：日期，股数，均价：[code, date, shares, value]
    // protected $handleBuyArr = [
    //     '002228' => ['002228', '20210720', 5000, 3.564], 
    //     '002345' => ['002345', '20210720', 3000, 5.85],
    //     '600016' => ['600016', '20210720', 2000, 4.17],
    //     '600668' => ['600668', '20210720', 1000, 11.14],
    //     '601186' => ['601186', '20210720', 5000, 7.29],
    //     '601336' => ['601336', '20210720', 1500, 44.48]
    // ];  
    // 买入记录，最小为三维数组。参数：日期，股数，均价：[code, date, shares, value]
    // protected $buyArr = [
    //     '002228' => [['002228', '20220622', 4000, 3.41]],
    // ];
// 卖出记录，最小为三维数组。参数：日期，股数，均价：[code, date, shares, value]
// protected $saleArr = [
//         '002228' => [['002228', '20221011', -6000, 3.10]],
//         '002345' => [['002345', '20230213', -1500, 6.55]]
//     ];
    

    // 股票数量少，手动处理比较快，写代码查询第一天的收盘价再拼数组也行。
    // 选择投资组合2，数组和字符串二选一
    protected $stockCodeArr = ['601319', '601688', '600623', '600810', '600475', '600585', '600104', '600997', '000725', '600028', '600398', '600308', '600782', '600000'];  
    protected $stockCodeStr = '601319,601688,600623,600810,600475,600585,600104,600997,000725,600028,600398,600308,600782,600000';
    protected $startTime = '220602';
    protected $stopTime = '230505';
    // 原始投资组合2，最小为二维数组。参数：日期，股数，均价：[code, date, shares, value]
    // 每只股票投资金额30000元，按100股为单位，向下取整。
    // protected $handleBuyArr = [
    //    '000725' => ['000725', '20220602', 7900, 3.76], 
    //    '600000' => ['600000', '20220602', 3800, 7.89], 
    //    '600028' => ['600028', '20220602', 6700, 4.42], 
    //    '600104' => ['600104', '20220602', 1600, 17.75], 
    //    '600308' => ['600308', '20220602', 5100, 5.88], 
    //    '600398' => ['600398', '20220602', 5700, 5.25], 
    //    '600475' => ['600475', '20220602', 3600, 8.33], 
    //    '600585' => ['600585', '20220602', 800, 36.22], 
    //    '600623' => ['600623', '20220602', 3400, 8.61], 
    //    '600782' => ['600782', '20220602', 5200, 5.75], 
    //    '600810' => ['600810', '20220602', 3200, 9.26], 
    //    '600997' => ['600997', '20220602', 4000, 7.42], 
    //    '601319' => ['601319', '20220602', 6500, 4.61], 
    //    '601688' => ['601688', '20220602', 2200, 13.32], 
    // ];
    protected $handleBuyArr = []; // 原始投资组合数组
    protected $buyArr = []; // 买入记录数组
    protected $saleArr = []; // 卖出记录数组

    // 公用变量
    protected $resultArr = []; // 投资组合处理过渡数组
    protected $finalArr = []; // 投资组合处理过渡数组
    protected $handleFinalArr = []; // 投资组合处理过渡数组

    protected $singlePrice = 30000;
    protected $unitGroup = 100;

    /**
     * @Func 股票投资组合最大回撤率：在选定周期内任一历史时点往后推，产品净值走到最低点时的收益率回撤幅度的最大值。
     * @Describe 导出图表和数据，也可能会拆分该函数
     * @Range 2021年7月20日-2023年3月20
     * @Time 2023/4/27
     * @Author WoodSmoke
     */
    public function maximumDrawdown ()
    {
        // 初始化买入数组，投资组合不会为空
        $orignArr = Daily::withSearch(['stockcode','date', 'close'], ['stockcode'=>$this->stockCodeStr, 'date' => $this->startTime])
                        ->field('date, stockcode, close') 
                        ->select();
        foreach ($orignArr->toArray() as $key => $value) {
            $this->handleBuyArr[$value['stockcode']] = [$value['stockcode'], $value['date'], floor($this->singlePrice / ($value['close'] * $this->unitGroup)) * 100, $value['close']];
        }

        // 获取指定股票收盘价，@return 包含模型的数据集
        $data = Daily::withSearch( ['date', 'stockcode'], 
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
                // 计算每天的持有股数,默认等于初始买入股数
                $this->resultArr[$val->stockcode]['shares'][$val->date] = $this->handleBuyArr[$val->stockcode][2];
                // 处理买入数据，每天
                if (!empty(array_filter($this->buyArr)) && !empty($this->buyArr[$val->stockcode])) {
                    foreach ($this->buyArr[$val->stockcode] as $k => $v) {
                        if ($v[1] <= $val->date) {
                            $this->resultArr[$val->stockcode]['shares'][$val->date] += $v[2];
                        } else {
                            $this->resultArr[$val->stockcode]['shares'][$val->date] += 0;
                        }
                    }
                }
                // 处理卖出数据，每天
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
                    // 收盘额，按每天持有股数统计。
                    $value['shoupane'][$v] = $value['shares'][$v] * $value['y'][$v];
                    // 计算均价,需要判断股票code存在
                    // 处理买入数据，每天
                    if (!empty(array_filter($this->buyArr)) && !empty($this->buyArr[$key])) {
                        foreach ($this->buyArr[$key] as $val) {
                            if ($val[1] <= $v) {
                                $this->resultArr[$key]['junjia'][$v] = round(
                                    ($originPriceAverage * $this->buyArr[$key][2] + $val[3] * $val[2]) // 持有价值
                                    / 
                                    $value['shares'][$v] // 持有股数
                                    , 2
                                );
                            }
                        }
                    }
                    // 处理卖出数据，每天
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
            // $orginAmountOuter = 0.00;
            // $shoupaneOuter = 0.00;
            // $shouyieOuter = 0.00;
            // $shouyilvOuter = 0.0000;            
            foreach ($this->resultArr as $key => &$value) {
                $yingkuieInner = 0.00;
                // $shouyieInner = 0.00;
                // $orginAmountInner = 0.00;
                foreach ($value['x'] as $k => $v) {
                    // 每天对应的原始投资额 = 每天的均价 * 每天的持有股数
                    $this->resultArr[$key]['orginAmount'][$v] = $value['junjia'][$v] * $value['shares'][$v];

                    // 每天的收盘额 = 每天的收盘价 * 每天的持有股数
                    $this->resultArr[$key]['shoupane'][$v] = $value['y'][$v] * $value['shares'][$v];
                    // 每天的收益额 = 每天的原始投资额 - 每天的收盘额 + 累计盈亏
                    $this->resultArr[$key]['shouyie'][$v] = round(
                        $this->resultArr[$key]['shoupane'][$v]
                        - 
                        $this->resultArr[$key]['orginAmount'][$v] 
                    , 2);
                    // 累计盈亏
                    if (!empty(array_filter($this->saleArr)) && !empty($this->saleArr[$key])) {
                        foreach ($this->saleArr[$key] as $val) {
                            if ($val[1] == $v) {
                                $yingkuieInner += $this->resultArr[$key]['shoupane'][$v] - $this->resultArr[$key]['orginAmount'][$v];                    
                            } 
                            $this->resultArr[$key]['yingkuie'][$v] = $yingkuieInner;
                        }
                    }
                    // 更新收益额
                    $this->resultArr[$key]['shouyie'][$v] = $this->resultArr[$key]['shouyie'][$v] + $yingkuieInner;

                    // 每天的收益率 = 每天的收益额 / 每天的原始投资额
                    $this->finalArr[$v][$key]['shouyie'] = $this->resultArr[$key]['shouyie'][$v];
                    $this->finalArr[$v][$key]['orginAmount'] = $this->resultArr[$key]['orginAmount'][$v];
                    $this->finalArr[$v][$key]['shoupane'] = $this->resultArr[$key]['shoupane'][$v];
                }

            }
        }

        // 取上一个foreach结果继续处理
        if (!empty(array_filter($this->finalArr))) {
            foreach ($this->finalArr as $key => $value) {
                $shouyie = 0.00;
                $orginAmount = 0.00;
                $shouyilv = 0.0000;
                $shoupane = 0.00;
                if (!empty(array_filter($value))) {
                    foreach ($value as $k => $v) {
                        $shouyie += $v['shouyie'];
                        $orginAmount += $v['orginAmount'];
                        $shoupane += $v['shoupane'];
                    }
                    $shouyilv = round($shouyie / $orginAmount, 4);
                }
                $this->handleFinalArr[$key]['shouyilv'] = $shouyilv;
                $this->handleFinalArr[$key]['shouyie'] = $shouyie;
                $this->handleFinalArr[$key]['orginAmount'] = $orginAmount;
                $this->handleFinalArr[$key]['shoupane'] = $shoupane;
            }
        }

        // 取上一个foreach结果继续处理
        if (!empty($this->handleFinalArr)) {
            $size = sizeof($this->handleFinalArr);
            $keys = array_keys($this->handleFinalArr);
            $keysReverse = array_flip($keys);
            foreach ($this->handleFinalArr as $key => &$value) {
                // 投资时间固定的最大回车率
                $forwardChilrenArr = array_slice(array_column($this->handleFinalArr, 'shouyie'), 0, $keysReverse[$key] + 1, true);
                $forwardMinShouyie = min($forwardChilrenArr);
                $forwardMaxShouyie = max($forwardChilrenArr);

                $value['forwardMaxHuichelv'] = round(
                    (
                        1 - abs($value['orginAmount'] + $forwardMinShouyie) 
                            / 
                            abs($value['orginAmount'] + $forwardMaxShouyie)
                    )
                , 4);
                if ($key == "20".$this->stopTime) {
                    $value['maxHuichelv'] = 0.0000;
                } else {
                    // 投资时间平移的最大回撤率
                    $childrenArr = array_slice(array_column($this->handleFinalArr, 'shouyie'), $keysReverse[$key], $size - $keysReverse[$key], true);
                    // 收益额放在数组第一个了，优先按收益额取min
                    $minShouyie = min($childrenArr);
                    // $maxShouyie = max($childrenArr);
                    if ($value['shouyie'] - $minShouyie >= 0) {
                        $value['maxHuichelv'] = round(
                            (
                                1 - abs($value['orginAmount'] + $minShouyie) 
                                    / 
                                    abs($value['orginAmount'] + $value['shouyie'])
                            )
                        , 4);
                    } else {
                        $value['maxHuichelv'] = 0.0000;
                    }
                }
                $this->handleFinalArr[$key] = $value;
            }
        }

        // ----------------【导出各列数据】---粘贴到txt----excel分列开始------------------
        dump("【收益率】");dump(array_column($this->handleFinalArr, 'shouyilv'));
        dump("【收益额】");dump(array_column($this->handleFinalArr, 'shouyie'));
        dump("【最大回撤率-投资日变化】");dump(array_column($this->handleFinalArr, 'maxHuichelv'));
        $dateArr = array_keys($this->handleFinalArr);
        dump("【最大回撤率-投资日固定】");dump(array_column($this->handleFinalArr, 'forwardMaxHuichelv'));
        // ----------------【导出各列数据】---粘贴到txt----excel分列结束------------------


        // 分析夏普比率 = {净值增长率的平均值 - 银行同期利率） / 净值增长率的标准差
            // 组合的预期收益率或者平均报酬率：净值增长率的平均值
            // 无风险报酬率：银行同期利率
            // 标准差或者波动率：净值增长率的标准差
        // 比较不同的组合有意义，超过最小无风险收益，每多承担一单位风险，对应有多少回报。
        // 计算标准差的具体方法如下：
            // 1. 计算每个月的收益率，即每个月的收益率 = （该月收盘价 - 上个月收盘价）/ 上个月收盘价
            // 2. 计算每个月的平均收益率，即平均收益率 = 所有月份的收益率之和 / 月份总数
            // 3. 计算每个月的收益率与平均收益率的差值，即每个月的差值 = 该月收益率 - 平均收益率
            // 4. 将每个月的差值平方，然后将所有的平方和加起来，得到方差
            // 5. 将方差除以月份总数，然后取平方根，得到标准差
        // 注意：在计算标准差时，需要将每个月的收益率转化为年化收益率，即年化收益率 = 月收益率 * 12。 

        // 设置组合预期收益率
        $expectRatio = 0.034300;
        // $expectRatio = 0.150000;
        // 设置银行同期利率
        $bankRatio = 0.030000;

        // -----------------------写法1开始-------------------------------------
        $latestDayObj = Daily::field('max(date) date')
                        ->whereBetween('date', [$this->startTime, $this->stopTime])
                        ->whereIN('stockcode', $this->stockCodeArr)
                        ->group('left(concat(20, date), 6)')
                        ->select()
                        ->withAttr('date', function($value, $data){
                            return '20'.$value;
                        });
        // 不用判断latestDayObj，初始数据一定不为空
        if ($this->startTime != $this->stopTime) {
            $latestDayArr = array_merge(
                array(
                    array('date' => '20'.$this->startTime)
                ), 
                $latestDayObj->toArray()
            );
        } else {
            // 经过动态获取器处理
            $latestDayArr = $latestDayObj->toArray();
        }
        // -----------------------写法1结束-------------------------------------

        // -----------------------写法2开始-------------------------------------
        // $latestDayObj = Daily::field('max(date) date')
        // ->whereBetween('date', [$this->startTime, $this->stopTime])
        // ->whereIN('stockcode', $this->stockCodeArr)
        // ->group('left(concat(20, date), 6)')
        // ->select();                        
        // // 不用判断latestDayObj，初始数据一定不为空
        // if ($this->startTime != $this->stopTime) {
        //     $latestDayArr = array_merge(array(array('date' => '20'.$this->startTime)), $latestDayObj->toArray());
        // } else {
        //     // 经过获取器处理
        //     $latestDayArr = $latestDayObj->toArray();
        // }
        // -----------------------写法2结束-------------------------------------
        
        foreach ($latestDayArr as $key => &$value) {
            $value['shoupane'] = $this->handleFinalArr[$value['date']]['shoupane'];
            $latestDayArr[$key] = $value;
        }
        // 1.月收益率，因为是组合投资，用收盘额取代收盘价计算更合理
        for ($i = 0; $i < count($latestDayArr) - 1; $i++) {
            $latestDayArr[0]['shouyilvMonth'] = 0.0000;
            $latestDayArr[$i+1]['shouyilvMonth'] = round(
                                                        ($latestDayArr[$i+1]['shoupane'] - $latestDayArr[$i]['shoupane'])  
                                                        / 
                                                        $latestDayArr[$i]['shoupane'], 
                                                    4);
        }

        // 2.平均收益率 3.差值、差值平方
        $shouyilvMonth = array_column($latestDayArr, 'shouyilvMonth');
        $shouyilvAverage = round(array_sum($shouyilvMonth) / (count($shouyilvMonth) - 1), 4);
        for ($i = 0; $i < count($latestDayArr) - 1; $i++) {
            $latestDayArr[0]['shouyilvAverage'] = 0.0000;
            $latestDayArr[$i+1]['shouyilvAverage'] = $shouyilvAverage;
            $latestDayArr[0]['shouyilvDiff'] = 0.0000;
            $latestDayArr[$i+1]['shouyilvDiff'] = $latestDayArr[$i+1]['shouyilvMonth'] - $shouyilvAverage;
            $latestDayArr[0]['shouyilvPow'] = 0.0000;
            $latestDayArr[$i+1]['shouyilvPow'] = round(pow($latestDayArr[$i+1]['shouyilvDiff'], 2), 6);
        }
        // 4.每个月的差值求方差
        $shouyilvPow = array_column($latestDayArr, 'shouyilvPow');
        $shouyilvSumPow = array_sum($shouyilvPow);
        // 5.求标准差或波动率
        $shouyilvStandardDeviation = round(sqrt($shouyilvSumPow / (count($shouyilvMonth) - 1)), 6);
        // 夏普比率
        $sharpeRatio = round(($expectRatio - $bankRatio) / $shouyilvStandardDeviation, 6);
        dump("【组合预期收益率】");dump($expectRatio);
        dump("【银行同期收益率】");dump($bankRatio);
        dump("【标准差或波动率】");dump($shouyilvStandardDeviation);
        dump("【夏普比率】");dump($sharpeRatio);
    }
}