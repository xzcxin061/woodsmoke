<?php
/*
 * @Author: chuiyan xzcxin061@163.com
 * @Date: 2022-04-08 16:51:27
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-05-15 17:43:22
 * @FilePath: /woodsmoke/app/common.php
 * @Description: 
 * 
 * Copyright (c) 2023 by ${user.name}/{user.email}, All Rights Reserved. 
 */

// 应用公共文件

/**
* 二维数组根据指定值分组
* @param $arr 需要进行分组的二维数组
* @param $keys 指定的键值
* @return array
*/
// function array_group_by($arr, $key){
//     $grouped = [];
//     foreach ($arr as $value){
//         $grouped[$value[$key]][] = $value;
//      }
//      if (func_num_args() > 2){
//          $args = func_get_args();
//          foreach ($grouped as $key => $value) {
//              $parms = array_merge([$value], array_slice($args, 2, func_num_args()));
//              $grouped[$key] = call_user_func_array('array_group_by', $parms);
//          }
//      }
//      return $grouped;
//  }

 /**
  * @author woodsmoke
  * 功能描述：获取已有日期中每个月的最后一天
  * @param $array 需要查询的数组
  * @param $status true|false 完整自然月的日期|当月部分日期
  * @return array 
  */
function get_maxdate_by_month($array, $status = false)
{
    // foreach () {
      
    // }
    // if ($status) {
      
    // } else {

    // }
}