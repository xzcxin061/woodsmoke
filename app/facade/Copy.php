<?php
namespace app\facade;

use think\Facade;

class Copy extends Facade
{
    protected static function getFacadeClass()
    {
        return 'app\controller\Copy';
    }
}