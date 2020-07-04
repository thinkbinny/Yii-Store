<?php

namespace weixin\services;



class Video extends Common {


    //接收视频消息
    protected function init(){
        $message = $this->message;
        $this->getResult =  '接收视频消息';
        return true;
    }
}