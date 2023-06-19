<?php
/*
 * @Author: chuiyan xzcxin061@163.com
 * @Date: 2022-04-08 16:51:27
 * @LastEditors: chuiyan xzcxin061@163.com
 * @LastEditTime: 2023-05-31 09:22:46
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
  * 功能描述：触发买入/卖出的条件
  * @param $object 需要查询的数组
  * @return array 
  */
function get_deal_condition($object, $param)
{
    
    // if(!empty($object)){
    //     $array = $object->toArray();
    //     foreach($array as $key => $value){
    //         // 更新初始价格
    //         $price = $array[0]['close'];
    //         if($key == 0){
    //             $dealArr = [];
    //         }else{
                

    //             // 检测是否触发卖出条件
    //             $array[$key]['high'] - $array[$key]['low'];
    //             // 检测是否触发买入条件

                
    //             $buynum = floor($param[0] / ($value['low'] * $param[1] * 2)) * $param[1];
    //             $dealArr[$value['stockcode']][] = [$value['stockcode'], $value['date'], $buynum, $value['low']];
    //         }
    //     }
    // }
    
}