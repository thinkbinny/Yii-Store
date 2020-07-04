<?php
namespace weixin\services;
use Yii;
/**
 * API服务端总入口
 * @author Thinkbinny <2017-8-5 12:20:01>
 */
class Common
{
    protected $message;

    protected $getResult;
    /**
     * 初始化
     * @param $message $error Error对象
     */
    public function __construct($message){
        $this->message = $message;
    }
    public function run(){
        $this->init();
        return $this->getResult;
    }

    protected function init(){
        $this->getResult =  '测试';
    }
}