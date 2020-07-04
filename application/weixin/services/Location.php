<?php

namespace weixin\services;



class Location extends Common {


    //接收位置消息
    protected function init(){
        $message = $this->message;
        $this->getResult = "你发送的是位置，经度为：".$message['Location_Y']."；纬度为：".$message['Location_X']."；缩放级别为：".$message['Scale']."；位置为：".$message['Label'];

    }
}