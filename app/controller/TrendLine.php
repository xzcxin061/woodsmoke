<?php 
namespace app\controller;

class TrendLine {
    private $arr;
    private $k = 0;
    private $b = 0;
    private $num = 0;

    /**
     * invoke方法使用
     * 对参数进行对象类型约束，请注释掉构造方法
     */
    public function __construct($arr) {
        if(is_array($arr) && !empty($arr)) {
            $this->num = count($arr);
            foreach($arr as $k=>$v) {
                $k = (string)($k+1);
                $this->arr[$k] = $v;
            }
        }
    }
    
    private function squareSumX() {
        $sum = 0;
        foreach($this->arr as $k=>$v) {
            $sum += pow($k, 2);
        }
        return $sum;
    }
     
    private function sumXY() {
        $sum = 0;
        foreach($this->arr as $k=>$v) {
            $sum += $k * $v;
        }
        return $sum;
    }
    
    private function calculateKB() {
        $sumx = array_sum(array_keys($this->arr));
        $sumy = array_sum($this->arr);
        $sumxy = $this->sumXY();
        $sumxx = $this->squareSumX();
        $this->k= round(($this->num*$sumxy-$sumx*$sumy)/($this->num*$sumxx-$sumx*$sumx), 4);
        $this->b=round(($sumxx*$sumy-$sumx*$sumxy)/($this->num*$sumxx-$sumx*$sumx), 3);
        return $this;
    }
    
    public function getKB() {
        $this->calculateKB();
        //趋势线 y=kx+b
        return ['k'=>$this->k, 'b'=>$this->b];
    }

    /**
     * 初始化变量$arr,替代构造方法
     */
    public function setParam($param)
    {
        $this->arr = $param;
        if(is_array($param) && !empty($param)) {
            $this->num = count($param);
            foreach($param as $k=>$v) {
                $k = (string)($k+1);
                $this->arr[$k] = $v;
            }
        }
    }
}