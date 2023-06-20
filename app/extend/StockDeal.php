<?php 
/*
 * @Author: chuiyan xzcxin061@163.com
 * @Date: 2023-05-25 11:55:52
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-06-20 10:58:15
 * @FilePath: /woodsmoke/app/extend/StockDeal.php
 * @Description: 
 * 
 * Copyright (c) 2023 by woodsmoke, All Rights Reserved. 
 */
namespace app\extend;
use app\model\Daily;

class StockDeal
{
    /**
     * @param $stockCodeArr 股票代码列表
     * @param $rangeArr 时间范围数组
     * @param $paramArr 综合参数数组
     */
    public function deal($stockCodeArr, $rangeArr, $paramArr) 
    {

        if (!empty($stockCodeArr)) {
            $finalArr = [];
            $dealArr = array();
            $dailyArr = [];
            foreach ($stockCodeArr as $key => $value) {
                // 按单个stockcode，多次查询，避免进行逻辑复杂的数组处理
                $dailyArr[$value] = Daily::withSearch(['stockcode', 'low', 'high', 'date', 'close'], ['stockcode' => $value, 'date' => $rangeArr])
                                ->field('date, stockcode, low, high, close')
                                ->select();
                $array = $dailyArr[$value]->toArray();
                if(!empty($array)){
                    // 初始价格
                    $price = $array[0]['close'];
                    $count = count($array);
                    if ($count > 0) {
                        $flag = 0;

                        for($i = 0; $i <= $count-1; $i++) {
                            
                            if ($i == 1) {
                                // 从第二天开始，设置初始值
                                $prevNum = floor($paramArr[0] / ($array[$i-1]['close'] * $paramArr[1])) * 100;
                                $dealArr[$array[$i]['stockcode']]['updateNum'] = $prevNum;
                                $dealArr[$array[$i]['stockcode']]['updatePrice'] = $array[$i-1]['close'];
                                
                                // 买入和卖出要同时检测
                                if($dealArr[$array[$i-1]['stockcode']]['status'] == 0){
                                    
                                    // 检测是否需要卖出
                                    // $diffSalePrice = round($array[$i]['high'] - $array[$i-1]['close'], 6);
                                    $diffSalePrice = round($array[$i]['close'] - $array[$i-1]['close'], 6);
                                    $salePecent = round($diffSalePrice / $price, 4);
                                    
                                    // 触发卖出条件
                                    if($salePecent - $paramArr[2] > 0){
                                        // 卖出记录：清仓，卖出股数等于买入股数
                                        // $finalArr[$value][] = [$array[$i]['stockcode'], $array[$i]['date'], 0 - $prevNum, $array[$i]['high']];
                                        $finalArr[$value][] = [$array[$i]['stockcode'], $array[$i]['date'], 0 - $prevNum, $array[$i]['close']];
                                        $dealArr[$array[$i]['stockcode']]['updateNum'] = 0;
                                        // $dealArr[$array[$i]['stockcode']]['updatePrice'] = $array[$i]['high'];
                                        $dealArr[$array[$i]['stockcode']]['updatePrice'] = $array[$i]['close'];
                                        // 停止该支股票后续加减仓操作
                                        $dealArr[$array[$i]['stockcode']]['status'] = 1;
                                        // return false;
                                    } else {
                                        // 检测是否需要买入
                                        // $diffBuyPrice = $array[$i]['low'] - $array[$i-1]['close'];
                                        $diffBuyPrice = $array[$i]['close'] - $array[$i-1]['close'];
                                        $buyPercent = round($diffBuyPrice / $array[0]['close'], 4);
                                        if($buyPercent - $paramArr[3] < 0){
                                            // 加仓股数
                                            // $buynum = floor($paramArr[0] * $paramArr[4] / ($array[$i]['low'] * $paramArr[1])) * 100;
                                            $buynum = floor($paramArr[0] * $paramArr[4] / ($array[$i]['close'] * $paramArr[1])) * 100;
                                            // 买入记录
                                            // $finalArr[$value][] = [$array[$i]['stockcode'], $array[$i]['date'], $buynum, $array[$i]['low']];
                                            $finalArr[$value][] = [$array[$i]['stockcode'], $array[$i]['date'], $buynum, $array[$i]['close']];
                                            // 更新持有股数
                                            $updateNum = $buynum + $prevNum;
                                            $dealArr[$array[$i]['stockcode']]['updateNum'] = $updateNum;
                                            // 更新买入价格(加权平均)
                                            // $dealArr[$array[$i]['stockcode']]['updatePrice'] = round(($prevNum * $array[$i-1]['close'] + $buynum * $array[$i]['low']) / $updateNum, 4);
                                            $dealArr[$array[$i]['stockcode']]['updatePrice'] = round(($prevNum * $array[$i-1]['close'] + $buynum * $array[$i]['close']) / $updateNum, 4);
                                            $dealArr[$array[$i]['stockcode']]['status'] = 0;
                                            $flag = $i;
                                        }
                                    }
                                }
                            } elseif($i == 0) {
                                $prevNum = floor($paramArr[0] / ($array[$i]['close'] * $paramArr[1])) * 100;
                                $dealArr[$array[$i]['stockcode']]['updateNum'] = $prevNum;
                                $dealArr[$array[$i]['stockcode']]['updatePrice'] = $array[$i]['close'];
                                // $finalArr[$value][] = [];
                                // 第一天没有买入和卖出的操作
                                $dealArr[$array[$i]['stockcode']]['status'] = 0;
                            } else {
                                // 检测是否已卖出
                                if($dealArr[$array[$i-1]['stockcode']]['status'] == 1){
                                    // 更新卖出数据
                                    $dealArr[$array[$i]['stockcode']]['updateNum'] = 0;
                                    $dealArr[$array[$i]['stockcode']]['updatePrice'] = $dealArr[$array[$i-1]['stockcode']]['updatePrice'];
                                    // $finalArr[$value][] = [];
                                    $dealArr[$array[$i]['stockcode']]['status'] = 1;
                                    // return false;
                                } else {
                                    // 检测是否需要卖出
                                    $updatePrice = $dealArr[$array[$i-1]['stockcode']]['updatePrice'];
                                    $updateNum = $dealArr[$array[$i-1]['stockcode']]['updateNum'];
                                    // $diffSalePrice = round($array[$i]['high'] - $updatePrice, 6);
                                    $diffSalePrice = round($array[$i]['close'] - $updatePrice, 6);
                                    $salePecent = round($diffSalePrice / $updatePrice, 4);
                                    if($salePecent - $paramArr[2] > 0){
                                        // 卖出记录：清仓，卖出股数等于买入股数
                                        // $finalArr[$value][] = [$array[$i]['stockcode'], $array[$i]['date'], 0 - $dealArr[$array[$i]['stockcode']]['updateNum'], $array[$i]['high']];
                                        $finalArr[$value][] = [$array[$i]['stockcode'], $array[$i]['date'], 0 - $dealArr[$array[$i]['stockcode']]['updateNum'], $array[$i]['close']];
                                        $dealArr[$array[$i]['stockcode']]['updateNum'] = 0;
                                        // $dealArr[$array[$i]['stockcode']]['updatePrice'] = $array[$i]['high'];
                                        $dealArr[$array[$i]['stockcode']]['updatePrice'] = $array[$i]['close'];
                                        // 停止该支股票后续加减仓操作
                                        $dealArr[$array[$i]['stockcode']]['status'] = 1;
                                    } else {
                                        // 检测是否需要买入
                                        // $diffSalePrice = round($array[$i]['low'] - $updatePrice, 6);
                                        $diffSalePrice = round($array[$i]['close'] - $updatePrice, 6);
                                        $buyPercent = round($diffSalePrice / $updatePrice, 4);
                                        if($buyPercent - $paramArr[3] < 0){
                                            // 加仓股数
                                            // $buynum = floor($paramArr[0] * $paramArr[4] / ($array[$i]['low'] * $paramArr[1])) * 100;
                                            $buynum = floor($paramArr[0] * $paramArr[4] / ($array[$i]['close'] * $paramArr[1])) * 100;
                                            // 买入记录
                                            // $finalArr[$value][] = [$array[$i]['stockcode'], $array[$i]['date'], $buynum, $array[$i]['low']];
                                            $finalArr[$value][] = [$array[$i]['stockcode'], $array[$i]['date'], $buynum, $array[$i]['close']];
                                            // 更新持有股数
                                            $dealArr[$array[$i]['stockcode']]['updateNum'] = $updateNum + $buynum;
                                            // 更新买入价格(加权平均)
                                            // $dealArr[$array[$i]['stockcode']]['updatePrice'] = round(
                                            //     (
                                            //         $updateNum * $updatePrice 
                                            //     + 
                                            //         $buynum * $array[$i]['low']
                                            //     ) 
                                            //     / 
                                            //     $dealArr[$array[$i]['stockcode']]['updateNum']
                                            // , 4);
                                            $dealArr[$array[$i]['stockcode']]['updatePrice'] = round(
                                                (
                                                    $updateNum * $updatePrice 
                                                + 
                                                    $buynum * $array[$i]['close']
                                                ) 
                                                / 
                                                $dealArr[$array[$i]['stockcode']]['updateNum']
                                            , 4);
                                            $dealArr[$array[$i]['stockcode']]['status'] = 0;
                                            $flag = $i;
                                        }
                                    }

                                }
                            }
                        } 
                    }
                }
            }
            return array($finalArr, $dealArr);
        }
    }
}