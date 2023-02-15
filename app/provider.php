<?php
use app\ExceptionHandle;
use app\Request;

// 容器Provider定义文件
return [
    // think\Request不能修改，因为request->think\Request后，又递归作为app\Request（即这里的Request::class）的标识，一定程度上说是写死了。解释的不见得准确，测试一下。
    // wood测试，改了think\Request标识为，app('think\Request')->param('id')调用不到
    'think\Request'          => Request::class,
    'think\exception\Handle' => ExceptionHandle::class,
];
