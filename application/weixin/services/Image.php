<?php

namespace weixin\services;



class Image extends Common {


    //接收图片消息
    protected function init(){
        $message = $this->message;
        $this->getResult =  '请问在吗';
        return true;
    }
}