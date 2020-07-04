<?php
namespace weixin\services;
use Yii;
/**
 * API服务端总入口
 * @author Thinkbinny <2017-8-5 12:20:01>
 */
class Server
{
    protected $receiveMessage;
    protected $MsgType;
    protected $getResult;
    //protected $MsgType;
    /**
     * 初始化
     * @param $message $error Error对象
     */
    public function __construct($message){
        $this->receiveMessage = $message;
        //$message = json_encode($message);
        //file_put_contents(YII_DIR.'/weixinTest.log', $message."\r\n", FILE_APPEND);
    }
    public function run(){
        $this->MsgType = $this->receiveMessage['MsgType'];
        $MsgType = [
          'text','image','location','voice','video','link','event'
        ];
        if(in_array($this->MsgType,$MsgType)){
            $className =  ucwords($this->MsgType);
            $classPath =  '\weixin\services\\'.$className ;

            if (!$className || !class_exists($classPath)) {
                return $this->getResult = '服务器系统出错';
            }
            $class  = new $classPath($this->receiveMessage);
            $this   ->getResult = $class->run();
            //print_r($this->getResult);exit;
        }else{
            $this->getResult = '服务器系统出错';
        }

    }
    public function getResult(){
        $this->run();
        return $this->getResult;
    }




}