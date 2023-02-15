<?php
/*
 * @Author: error: git config user.name && git config user.email & please set dead value or install git
 * @Date: 2022-04-08 16:51:27
 * @LastEditors: error: git config user.name && git config user.email & please set dead value or install git
 * @LastEditTime: 2022-10-21 17:53:43
 * @FilePath: /woodsmoke/config/view.php
 * @Description: 
 * 
 * Copyright (c) 2022 by error: git config user.name && git config user.email & please set dead value or install git, All Rights Reserved. 
 */
// +----------------------------------------------------------------------
// | 模板设置
// +----------------------------------------------------------------------

return [
    // 模板引擎类型使用Think
    'type'          => 'Think',
    // 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写 3 保持操作方法
    'auto_rule'     => 1,
    // 模板目录名
    'view_dir_name' => 'view',
    // 模板后缀
    'view_suffix'   => 'html',
    // 模板文件名分隔符
    'view_depr'     => DIRECTORY_SEPARATOR,
    // 模板引擎普通标签开始标记
    'tpl_begin'     => '{',
    // 模板引擎普通标签结束标记
    'tpl_end'       => '}',
    // 是否开启模板编译缓存
    // 'tpl_cache'     => false,
    // 标签库标签开始标记
    'taglib_begin'  => '{',
    // 标签库标签结束标记
    'taglib_end'    => '}',
    // 【手动】开启layout布局，默认不开启
    'layout_on'     => 'true',
    'layout_name'   => 'layout',
    // 【手动】默认{__CONTENT__}，可替换成其他如：{__REPLACE__}
    // 'layout_item'   =>  '{__CONTENT__}',
    // 'cache_time'    => 0,
];
