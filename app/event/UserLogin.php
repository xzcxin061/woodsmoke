<?php
declare (strict_types = 1);

namespace app\event;
use app\model\User;

class UserLogin
{
    public $user;
    public function __construct(User $user)
    {
        $this->user = $user;
        echo "【app\\event\UserLogin】";echo $this->user;echo "这里使用事件类！";echo "<br/>";
    }
}
