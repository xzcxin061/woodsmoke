<?php
declare (strict_types = 1);

namespace app\listener;

class UserLogin
{
    /**
     * 事件监听处理
     *
     * @return mixed
     */
    public function handle($event)
    {
        // 事件监听处理
        echo "【app\listener】";echo "这是一个事件监听！";echo "<br/>";
    }
}
