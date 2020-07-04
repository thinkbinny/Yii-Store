<?php

namespace weixin\services;



class Voice extends Common {


    //接收语音消息
    protected function init(){
        $message = $this->message;
        $this->getResult =  '接收语音消息';
        return true;
    }
}