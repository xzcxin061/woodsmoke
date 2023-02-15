<?php
// 全局中间件定义文件
return [
    // 全局请求缓存
    \think\middleware\CheckRequestCache::class,
    // 多语言加载
    // \think\middleware\LoadLangPack::class,
    // Session初始化
    // \think\middleware\SessionInit::class,
    // 预定义中间件（其实就是增加别名标识）
    // 测试加载全局中间件,别名参考文档去config\middleware中定义
    // \app\middleware\Auth::class,
    // \app\middleware\Check::class,
];
