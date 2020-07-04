<?php

namespace weixin\services;



class Link extends Common {


    //接收链接消息
    protected function init(){
        $message = $this->message;

        $this->getResult = "你发送的是链接，标题为：".$message['Title']."；内容为：".$message['Description']."；链接地址为：".$message['Url'];

        return true;
    }
}